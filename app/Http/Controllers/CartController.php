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
        $items = $request->user()
            ->cartItems()
            ->with('product')
            ->latest()
            ->get();

        // Hapus produk yang sudah tidak tersedia.
        $unavailable = $items->filter(
            fn ($item) => ! $item->product || ! $item->product->is_active
        );

        if ($unavailable->isNotEmpty()) {
            CartItem::whereIn('id', $unavailable->pluck('id'))->delete();

            $items = $items->reject(
                fn ($item) => $unavailable->contains('id', $item->id)
            );
        }

        // Tandai produk yang jumlahnya melebihi stok.
        $items->each(function ($item) {
            $item->setAttribute(
                'checkout_stock_ok',
                $item->quantity <= $item->product->stock
            );
        });

        // Total semua produk sebelum pelanggan memilih.
        $total = $items->sum(
            fn ($item) => $item->subtotal
        );

        $hasStockProblem = $items->contains(
            fn ($item) => ! $item->checkout_stock_ok
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

        $product = Product::active()->findOrFail($data['product_id']);

        if ($product->stock < 1) {
            return back()->with('error', 'Stok produk ini habis.');
        }

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
        abort_unless(
            $cartItem->user_id === $request->user()->id,
            403
        );

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
            return back()->with(
                'error',
                "Stok {$product->name} tersisa {$product->stock}."
            );
        }

        $cartItem->update([
            'quantity' => $data['quantity']
        ]);

        return back();
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        abort_unless(
            $cartItem->user_id === $request->user()->id,
            403
        );

        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}