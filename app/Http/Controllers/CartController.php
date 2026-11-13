<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $items = $request->user()->cartItems()->with('product')->latest()->get();

        // Produk yang dihapus atau dinonaktifkan admin tidak bisa dibeli lagi,
        // jadi dibersihkan dari keranjang agar total tidak menyesatkan.
        $unavailable = $items->filter(
            fn ($item) => ! $item->product || ! $item->product->is_active
        );

        if ($unavailable->isNotEmpty()) {
            CartItem::whereIn('id', $unavailable->pluck('id'))->delete();
            $items = $items->reject(
                fn ($item) => $unavailable->contains('id', $item->id)
            );
        }

        // Total dihitung dari harga produk saat ini, bukan dari input browser.
        $total = $items->sum(fn ($item) => $item->product->price * $item->quantity);

        // Admin bisa menurunkan stok setelah barang masuk keranjang.
        $hasStockProblem = $items->contains(
            fn ($item) => $item->quantity > $item->product->stock
        );

        return view('cart.index', [
            'items' => $items,
            'total' => $total,
            'removedCount' => $unavailable->count(),
            'hasStockProblem' => $hasStockProblem,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        // Produk yang di-soft-delete tidak ditemukan; active() menolak yang nonaktif.
        $product = Product::active()->findOrFail($data['product_id']);

        if ($product->stock < 1) {
            return back()->with('error', 'Stok produk ini habis.');
        }

        // Satu baris per (user, produk); menambah lagi berarti menambah jumlah.
        $item = CartItem::firstOrNew([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
        ]);

        $inCart = $item->quantity ?? 0;
        $newQuantity = $inCart + $data['quantity'];

        if ($newQuantity > $product->stock) {
            return back()->with(
                'error',
                "Stok tersisa {$product->stock}, dan di keranjangmu sudah ada {$inCart}."
            );
        }

        $item->quantity = $newQuantity;
        $item->save();

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        // Mencegah user mengubah keranjang milik orang lain.
        abort_unless($cartItem->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $product = $cartItem->product;

        if (! $product || ! $product->is_active) {
            $cartItem->delete();

            return redirect()->route('cart.index')
                ->with('error', 'Produk sudah tidak tersedia dan dihapus dari keranjang.');
        }

        if ($data['quantity'] > $product->stock) {
            return back()->with('error', "Stok {$product->name} tersisa {$product->stock}.");
        }

        $cartItem->update(['quantity' => $data['quantity']]);

        return back();
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);

        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}