<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductMarketplaceLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('marketplaceLinks')
            ->where('is_active', true)
            ->paginate(20);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'size' => ['nullable', 'string', 'max:50'],
            'difficulty_level' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'marketplace_links.tokopedia' => ['nullable', 'url'],
            'marketplace_links.shopee' => ['nullable', 'url'],
            'marketplace_links.tiktok_shop' => ['nullable', 'url'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product = Product::create($validated);

        $this->syncMarketplaceLinks($product, $validated['marketplace_links'] ?? []);

        return response()->json($product->load('marketplaceLinks'), 201);
    }

    public function show(Product $product)
    {
        $product->load('marketplaceLinks');
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'unique:products,slug,' . $product->id],
            'sku' => ['sometimes', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'price' => ['sometimes', 'integer', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:100'],
            'brand' => ['nullable', 'string', 'max:100'],
            'size' => ['nullable', 'string', 'max:50'],
            'difficulty_level' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'marketplace_links.tokopedia' => ['nullable', 'url'],
            'marketplace_links.shopee' => ['nullable', 'url'],
            'marketplace_links.tiktok_shop' => ['nullable', 'url'],
        ]);

        if (isset($validated['name']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $product->update($validated);

        if (array_key_exists('marketplace_links', $validated)) {
            $this->syncMarketplaceLinks($product, $validated['marketplace_links'] ?? []);
        }

        return response()->json($product->refresh()->load('marketplaceLinks'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Deleted']);
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
}

