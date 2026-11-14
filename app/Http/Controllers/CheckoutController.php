<?php

namespace App\Http\Controllers;

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
    public function index(Request $request): View|RedirectResponse
    {
        $items = $request->user()->cartItems()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjangmu masih kosong.');
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

        $total = $items->sum(fn ($item) => $item->product->price * $item->quantity);

        return view('checkout.index', compact('items', 'total'));
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        try {
            $order = DB::transaction(function () use ($user, $data) {
                $cartItems = CartItem::where('user_id', $user->id)->get();

                if ($cartItems->isEmpty()) {
                    throw new DomainException('Keranjangmu masih kosong.');
                }

                // Baris produk dikunci sampai transaksi selesai, supaya dua pembeli
                // tidak bisa mengambil stok terakhir yang sama secara bersamaan.
                $products = Product::whereIn('id', $cartItems->pluck('product_id'))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $total = 0;
                $lines = [];

                foreach ($cartItems as $cartItem) {
                    $product = $products->get($cartItem->product_id);

                    if (! $product || ! $product->is_active) {
                        throw new DomainException('Ada produk yang sudah tidak tersedia. Periksa keranjangmu.');
                    }

                    if ($cartItem->quantity > $product->stock) {
                        throw new DomainException("Stok {$product->name} tersisa {$product->stock}.");
                    }

                    // Harga selalu dari database; form checkout tidak membawa harga atau total.
                    $subtotal = $product->price * $cartItem->quantity;
                    $total += $subtotal;

                    $lines[] = [
                        'product' => $product,
                        'quantity' => $cartItem->quantity,
                        'subtotal' => $subtotal,
                    ];
                }

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

                    // Nama dan harga disalin agar pesanan lama tidak berubah saat harga produk berubah.
                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $line['quantity'],
                        'subtotal' => $line['subtotal'],
                    ]);

                    // Stok "dipesan" sejak sekarang; dikembalikan bila pembayaran gagal atau kedaluwarsa.
                    $product->decrement('stock', $line['quantity']);
                }

                CartItem::where('user_id', $user->id)->delete();

                return $order;
            });
        } catch (DomainException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
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