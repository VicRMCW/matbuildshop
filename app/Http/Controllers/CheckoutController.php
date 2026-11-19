<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Requests\StoreCheckoutRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    // ---- Memproses pilihan produk dari keranjang ----

    public function prepare(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'cart_item_ids' => ['required', 'array', 'min:1'],
            'cart_item_ids.*' => ['required', 'integer', 'distinct'],
        ]);

        $selectedIds = collect($data['cart_item_ids'])
            ->map(fn ($id) => (int) $id)
            ->values();

        // Pastikan produk benar-benar milik pelanggan yang login.
        $items = $request->user()
            ->cartItems()
            ->with('product')
            ->whereIn('id', $selectedIds)
            ->get();

        if ($items->count() !== $selectedIds->count()) {
            return redirect()->route('cart.index')
                ->with('error', 'Pilihan produk tidak valid. Silakan pilih ulang.');
        }

        // Periksa ketersediaan dan stok produk.
        $problem = $items->first(
            fn ($item) => ! $item->product
                || ! $item->product->is_active
                || $item->quantity > $item->product->stock
        );

        if ($problem) {
            return redirect()->route('cart.index')
                ->with('error', 'Ada produk yang tidak tersedia atau melebihi stok.');
        }

        // Simpan ID produk terpilih ke session.
        $request->session()->put(
            'checkout_cart_item_ids',
            $items->pluck('id')->map(fn ($id) => (int) $id)->all()
        );

        return redirect()->route('checkout.index');
    }


    // ---- Checkout dari keranjang ----

    public function index(Request $request): View|RedirectResponse
    {
        // Ambil hanya ID produk yang sebelumnya dipilih.
        $selectedIds = collect(
            $request->session()->get('checkout_cart_item_ids', [])
        )
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($selectedIds->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Pilih produk yang ingin dibeli terlebih dahulu.');
        }

        $items = $request->user()
            ->cartItems()
            ->with('product')
            ->whereIn('id', $selectedIds)
            ->get();

        if ($items->count() !== $selectedIds->count()) {
            $request->session()->forget('checkout_cart_item_ids');

            return redirect()->route('cart.index')
                ->with('error', 'Pilihan produk berubah. Silakan pilih ulang.');
        }

        $problem = $items->first(
            fn ($item) => ! $item->product
                || ! $item->product->is_active
                || $item->quantity > $item->product->stock
        );

        if ($problem) {
            return redirect()->route('cart.index')
                ->with('error', 'Ada produk yang tidak tersedia atau melebihi stok. Periksa keranjangmu dulu.');
        }

        $total = $items->sum(
            fn ($item) => $item->product->price * $item->quantity
        );

        return view('checkout.index', compact('items', 'total'));
    }


    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        return $this->placeOrder($request, 'cart');
    }


    // ---- Beli sekarang (tanpa melalui keranjang) ----

    public function startDirect(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $product = Product::active()->findOrFail($data['product_id']);

        if ($data['quantity'] > $product->stock) {
            return back()->with('error', "Stok tersisa {$product->stock}.");
        }

        $request->session()->put('direct_checkout', [
            'product_id' => $product->id,
            'quantity' => (int) $data['quantity'],
        ]);

        return redirect()->route('checkout.direct');
    }


    public function direct(Request $request): Response|RedirectResponse
    {
        $direct = $request->session()->get('direct_checkout');

        if (! $direct) {
            return redirect()->route('shop.home')
                ->with('error', 'Tidak ada produk untuk dibeli. Pilih produk terlebih dahulu.');
        }

        $product = Product::active()->find($direct['product_id']);

        if (! $product || $direct['quantity'] > $product->stock) {
            $request->session()->forget('direct_checkout');

            return redirect()->route('shop.home')
                ->with('error', 'Produk tidak tersedia atau stok berubah. Silakan pilih lagi.');
        }

        $quantity = $direct['quantity'];
        $total = $product->price * $quantity;

        $items = collect([(object) [
            'product' => $product,
            'quantity' => $quantity,
            'subtotal' => $total,
        ]]);

        return response()->view('checkout.index', [
            'items' => $items,
            'total' => $total,
            'formAction' => route('checkout.direct.store'),
            'backUrl' => route('shop.products.show', ['product' => $product->slug]),
            'backLabel' => 'Kembali ke produk',
        ])->header('Cache-Control', 'no-store, max-age=0');
    }


    public function storeDirect(StoreCheckoutRequest $request): RedirectResponse
    {
        return $this->placeOrder($request, 'direct');
    }


    // ---- Pembuatan pesanan (dipakai kedua jalur) ----

    private function placeOrder(
        StoreCheckoutRequest $request,
        string $source
    ): RedirectResponse {

        $user = $request->user();
        $data = $request->validated();
        $direct = $request->session()->get('direct_checkout');

        // Ambil ID produk pilihan dari session.
        $selectedCartItemIds = collect(
            $source === 'cart'
                ? $request->session()->get('checkout_cart_item_ids', [])
                : []
        )
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($source === 'direct' && ! $direct) {
            return redirect()->route('shop.home')
                ->with('error', 'Tidak ada produk untuk dibeli. Pilih produk terlebih dahulu.');
        }

        if ($source === 'cart' && $selectedCartItemIds->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Pilih minimal satu produk untuk checkout.');
        }

        try {

            $order = DB::transaction(function () use (
                $user,
                $data,
                $source,
                $direct,
                $selectedCartItemIds
            ) {

                // Sumber barang: produk pilihan atau satu produk Beli Sekarang.
                if ($source === 'cart') {

                    $cartItems = CartItem::where('user_id', $user->id)
                        ->whereIn('id', $selectedCartItemIds)
                        ->lockForUpdate()
                        ->get();

                    if ($cartItems->count() !== $selectedCartItemIds->count()) {
                        throw new DomainException(
                            'Pilihan produk berubah. Silakan pilih ulang dari keranjang.'
                        );
                    }

                    $wanted = $cartItems->map(
                        fn ($item) => [
                            'cart_item_id' => $item->id,
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                        ]
                    );

                } else {

                    $wanted = collect([$direct]);

                }

                if ($wanted->isEmpty()) {
                    throw new DomainException('Tidak ada produk yang dipilih.');
                }

                // Kunci baris produk selama transaksi.
                $products = Product::whereIn(
                    'id',
                    $wanted->pluck('product_id')
                )
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $total = 0;
                $lines = [];

                foreach ($wanted as $want) {

                    $product = $products->get($want['product_id']);

                    if (! $product || ! $product->is_active) {
                        throw new DomainException(
                            'Ada produk yang sudah tidak tersedia. Silakan periksa lagi.'
                        );
                    }

                    if ($want['quantity'] > $product->stock) {
                        throw new DomainException(
                            "Stok {$product->name} tersisa {$product->stock}."
                        );
                    }

                    // Harga selalu dihitung dari database.
                    $subtotal = $product->price * $want['quantity'];

                    $total += $subtotal;

                    $lines[] = [
                        'product' => $product,
                        'quantity' => $want['quantity'],
                        'subtotal' => $subtotal,
                    ];
                }

                // Membuat pesanan.
                $order = Order::create([
                    'user_id' => $user->id,
                    'order_code' => $this->generateOrderCode(),
                    'recipient_name' => $data['recipient_name'],
                    'phone' => $data['phone'],
                    'shipping_address' => $data['shipping_address'],
                    'total_amount' => $total,
                ]);

                foreach ($lines as $line) {

                    $product = $line['product'];

                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $line['quantity'],
                        'subtotal' => $line['subtotal'],
                    ]);

                    // Kurangi stok produk.
                    $product->decrement('stock', $line['quantity']);
                }

                // Hanya hapus produk yang dipilih dan berhasil dipesan.
                if ($source === 'cart') {
                    CartItem::where('user_id', $user->id)
                        ->whereIn('id', $selectedCartItemIds)
                        ->delete();
                }

                return $order;
            });

        } catch (DomainException $e) {

            if ($source === 'direct') {
                $request->session()->forget('direct_checkout');
            }

            return redirect()
                ->route($source === 'cart' ? 'cart.index' : 'shop.home')
                ->with('error', $e->getMessage());
        }

        // Bersihkan session setelah pesanan berhasil dibuat.
        $request->session()->forget('direct_checkout');

        if ($source === 'cart') {
            $request->session()->forget('checkout_cart_item_ids');
        }

        return redirect()
            ->route('orders.show', ['order' => $order->order_code])
            ->with('success', "Pesanan {$order->order_code} berhasil dibuat.");
    }


    private function generateOrderCode(): string
    {
        do {
            $code = 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while (Order::where('order_code', $code)->exists());

        return $code;
    }
}
