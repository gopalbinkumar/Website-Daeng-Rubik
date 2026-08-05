<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $contact = ContactUs::current();
        $productBrands = ProductBrand::orderByRaw("CASE WHEN name = 'Lainnya' THEN 1 ELSE 0 END")
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $productCategories = ProductCategory::orderByRaw("CASE WHEN name = 'Lainnya' THEN 1 ELSE 0 END")
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.settings', compact('contact', 'productBrands', 'productCategories'));
    }

    public function updateContact(Request $request)
    {
        $validated = $request->validate([
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:30',
            'whatsapp_number' => 'nullable|string|max:30',
            'whatsapp_url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $contact = ContactUs::query()->first();

        if ($contact) {
            $contact->update($validated);
        } else {
            ContactUs::create($validated);
        }

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Kontak berhasil disimpan');
    }

    public function destroyContact()
    {
        ContactUs::query()->delete();

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Kontak berhasil dihapus');
    }

    public function storeBrand(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:product_brands,name',
            'is_active' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        ProductBrand::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_active' => $validated['is_active'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Merk produk berhasil ditambahkan');
    }

    public function updateBrand(Request $request, ProductBrand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:product_brands,name,' . $brand->id,
            'is_active' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $oldName = $brand->name;

        $brand->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_active' => $validated['is_active'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        if ($oldName !== $brand->name) {
            Product::where('brand', $oldName)->update(['brand' => $brand->name]);
        }

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Merk produk berhasil diperbarui');
    }

    public function destroyBrand(ProductBrand $brand)
    {
        if (Product::where('brand', $brand->name)->exists()) {
            return redirect()
                ->route('admin.settings')
                ->with('error', 'Merk tidak dapat dihapus karena masih dipakai produk.');
        }

        $brand->delete();

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Merk produk berhasil dihapus');
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:product_categories,name',
            'is_active' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        ProductCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_active' => $validated['is_active'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Kategori produk berhasil ditambahkan');
    }

    public function updateCategory(Request $request, ProductCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:product_categories,name,' . $category->id,
            'is_active' => 'required|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_active' => $validated['is_active'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Kategori produk berhasil diperbarui');
    }

    public function destroyCategory(ProductCategory $category)
    {
        if ($category->products()->exists()) {
            return redirect()
                ->route('admin.settings')
                ->with('error', 'Kategori tidak dapat dihapus karena masih dipakai produk.');
        }

        $category->delete();

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Kategori produk berhasil dihapus');
    }
}
