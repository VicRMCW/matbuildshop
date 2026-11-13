<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        // Sanitasi parameter pencarian untuk mencegah pola wildcard berlebihan dan membatasi beban LIKE query.
        $search = $request->filled('q') ? mb_substr(trim((string) $request->input('q')), 0, 100) : null;
        $activeCategorySlug = $request->input('kategori');

        $products = Product::active()
            ->with('category')
            ->when($search, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($activeCategorySlug, function ($query, $slug) {
                // Gunakan whereHas agar jika slug kategori tidak valid, filter otomatis diabaikan tanpa error 404/500.
                $query->whereHas('category', function ($sub) use ($slug) {
                    $sub->where('slug', $slug);
                });
            })
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        // Hitung produk aktif per kategori saja agar angka badge tidak membingungkan pengunjung.
        $categories = Category::withCount(['products' => function ($query) {
            $query->where('is_active', true);
        }])
            ->orderBy('name')
            ->get();

        return view('shop.home', [
            'products'           => $products,
            'categories'         => $categories,
            'q'                  => $search,
            'activeCategorySlug' => $activeCategorySlug,
        ]);
    }

    public function show(Product $product): View
    {
        // Blokir akses publik ke produk nonaktif meskipun URL slug-nya ditebak langsung.
        if (! $product->is_active) {
            abort(404);
        }

        $product->load('category');

        return view('shop.products.show', compact('product'));
    }
}
