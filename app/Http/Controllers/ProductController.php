<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductMarketplaceLink;
use App\Models\CubeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\TransactionItem;
use Illuminate\Database\QueryException;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('marketplaceLinks')
            ->where('is_active', true)
            ->paginate(20);

        return response()->json($products);
    }

    public function adminIndex(Request $request)
    {
        $query = Product::with(['primaryImage', 'cubeCategory']);

        /* =========================
           SEARCH (NAMA PRODUK)
        ========================= */
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        /* =========================
           FILTER KATEGORI (cube_categories)
        ========================= */
        if ($request->filled('category')) {
            $query->where('cube_category_id', $request->category);
        }

        /* =========================
           SORTING
        ========================= */
        switch ($request->sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;

            case 'price_high':
                $query->orderBy('price', 'desc');
                break;

            default:
                $query->latest(); // created_at DESC
                break;
        }

        /* =========================
           PAGINATION
        ========================= */
        $products = $query
            ->paginate(10)
            ->withQueryString(); // 🔥 filter & search tetap saat pindah halaman

        /* =========================
           DATA KATEGORI (UNTUK FILTER)
        ========================= */
        $cubeCategories = CubeCategory::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'cubeCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'cube_category_id' => 'required|exists:cube_categories,id',
            'brand' => 'required|string|max:100',
            'difficulty_level' => 'required|string|max:50',
            'description' => 'required|string',
            'is_active' => 'boolean',

            'images' => 'required|array|max:3',
            'images.*' => 'image|max:4096',

            'marketplace_links.tokopedia' => 'nullable|url',
            'marketplace_links.shopee' => 'nullable|url',
            'marketplace_links.tiktok_shop' => 'nullable|url',
        ]);

        DB::transaction(function () use ($validated, $request, &$product) {

            $product = Product::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'cube_category_id' => $validated['cube_category_id'],
                'brand' => $validated['brand'],
                'difficulty_level' => $validated['difficulty_level'],
                'description' => $validated['description'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // 🖼️ SIMPAN GAMBAR
            foreach ($request->file('images') as $index => $image) {
                if (!$image)
                    continue;

                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'position' => $index,        // 🔥 INI WAJIB
                    'is_primary' => $index === 0,
                ]);
            }


            $this->syncMarketplaceLinks(
                $product,
                $request->input('marketplace_links', [])
            );
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }


    public function show(Product $product)
    {
        $product->load('marketplaceLinks');
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'cube_category_id' => 'required|exists:cube_categories,id',
            'brand' => 'required',
            'difficulty_level' => 'required',
            'description' => 'required',
            'is_active' => 'boolean',
            'images.*' => 'nullable|image|max:4096',

            // 🔥 TAMBAHKAN INI
            'marketplace_links.tokopedia' => 'nullable|url',
            'marketplace_links.shopee' => 'nullable|url',
            'marketplace_links.tiktok_shop' => 'nullable|url',
        ]);

        $pendingQty = TransactionItem::whereHas('transaction', function ($q) {
            $q->where('status', 'pending');
        })
            ->where('product_id', $product->id)
            ->sum('quantity');

        if ($validated['stock'] < $pendingQty) {
            return back()->with(
                'error',
                "Stok tidak boleh lebih kecil dari total order pending ($pendingQty)"
            );
        }

        $product->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],

            // 🔥 UPDATE FK
            'cube_category_id' => $validated['cube_category_id'],

            'brand' => $validated['brand'],
            'difficulty_level' => $validated['difficulty_level'],
            'description' => $validated['description'],
            'is_active' => $validated['is_active'] ?? false,
        ]);

        // Sinkronkan harga di semua cart aktif
        CartItem::where('product_id', $product->id)
            ->whereHas('cart', function ($q) {
                $q->where('status', 'active');
            })
            ->update([
                'unit_price' => $product->price
            ]);


        // LOOP SLOT TETAP 0–2
        foreach ([0, 1, 2] as $position) {

            // kalau slot ini tidak diupload → LEWATI
            if (!$request->hasFile("images.$position")) {
                continue;
            }

            $image = $request->file("images.$position");

            // cari gambar lama DI SLOT INI
            $oldImage = $product->images()
                ->where('position', $position)
                ->first();

            // hapus gambar lama (jika ada)
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
                $oldImage->delete();
            }

            // simpan gambar baru
            $path = $image->store('products', 'public');

            $product->images()->create([
                'image_path' => $path,
                'position' => $position,
                'is_primary' => $position === 0,
            ]);
        }

        $this->syncMarketplaceLinks(
            $product,
            $request->input('marketplace_links', [])
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        // 🔥 Cek apakah ada transaksi pending
        $hasPending = $product->transactionItems()
            ->whereHas('transaction', function ($q) {
                $q->where('status', 'pending');
            })
            ->exists();

        if ($hasPending) {
            return redirect()
                ->route('admin.products.index')
                ->with(
                    'error',
                    'Produk tidak dapat dihapus karena masih memiliki transaksi pending.'
                );
        }

        // 🔥 Soft delete
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }


    protected function syncMarketplaceLinks(Product $product, array $links): void
    {
        $map = [
            'tokopedia' => ProductMarketplaceLink::MARKETPLACE_TOKOPEDIA,
            'shopee' => ProductMarketplaceLink::MARKETPLACE_SHOPEE,
            'tiktok_shop' => ProductMarketplaceLink::MARKETPLACE_TIKTOK,
        ];

        foreach ($map as $key => $marketplace) {
            $url = $links[$key] ?? null;

            if ($url) {
                ProductMarketplaceLink::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'marketplace' => $marketplace,
                    ],
                    ['url' => $url]
                );
            } else {
                ProductMarketplaceLink::where('product_id', $product->id)
                    ->where('marketplace', $marketplace)
                    ->delete();
            }
        }
    }

    public function userIndex(Request $request)
    {
        $query = Product::with(['primaryImage', 'cubeCategory'])
            ->where('is_active', true);


        // 🔎 FILTER KATEGORI (MULTI)
        if ($request->filled('category')) {
            $query->whereIn('cube_category_id', $request->category);
        }

        /* =========================
           FILTER BRAND (ARRAY)
        ========================= */
        if ($request->filled('brand') && is_array($request->brand)) {
            $query->whereIn('brand', $request->brand);
        }

        /* =========================
           FILTER HARGA
        ========================= */
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (int) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (int) $request->max_price);
        }

        /* =========================
           SORTING
        ========================= */
        switch ($request->sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;

            case 'price_high':
                $query->orderBy('price', 'desc');
                break;

            default:
                $query->latest(); // created_at DESC
                break;
        }

        /* =========================
   SEARCH PRODUK (NAMA)
========================= */
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        /* =========================
           PAGINATION
        ========================= */
        $products = $query
            ->paginate(12)
            ->withQueryString(); // 🔥 filter tidak hilang saat pindah halaman

        $cubeCategories = CubeCategory::orderByRaw
        ("
        CASE 
            WHEN name = 'Lainnya' THEN 1
            ELSE 0
        END
        ")->orderBy('name')->get();
        return view('pages.products', compact('products', 'cubeCategories'));
    }

}

