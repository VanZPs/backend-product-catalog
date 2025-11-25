<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class ProductPublicController extends Controller
{
    /**
     * Public catalog
     */
    public function index(Request $request)
    {
        $version = Cache::get('products_cache_version', 1);

        $cacheKey = "products:index:v{$version}:page:{$request->get('page',1)}";

        $products = Cache::remember($cacheKey, 60, function () use ($request) {
            return Product::withAvg('reviews', 'rating')
            ->select(
                'product_id',
                'name',
                'slug',
                'price',
                'images',
                'primary_image',
                'created_at'
            )
            ->orderBy('product_id', 'desc')
            ->paginate(20);
        });

        // Normalize average rating into `average_rating` float
        $products->getCollection()->transform(function ($p) {
            $p->average_rating = $p->reviews_avg_rating ? round((float) $p->reviews_avg_rating, 2) : null;
            return $p;
        });

        return response()->json($products);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $product->increment('visitor');

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'product_id'  => $product->product_id,
            'name'        => $product->name,
            'slug'        => $product->slug,
            'description' => $product->description,
            'price'       => $product->price,
            'stock'       => $product->stock,
            'images'      => $product->images,
            'seller'      => [
                'seller_id'   => $product->seller->seller_id,
                'store_name'  => $product->seller->store_name,
                'city'        => $product->seller->city?->name,
                'province'    => $product->seller->province?->name,
            ],
            'created_at'  => $product->created_at,
        ]);
    }


    public function search(Request $request)
    {
        $query = Product::query();

        // is_active filter
        if (Schema::hasColumn('products', 'is_active')) {
            $query->where('is_active', true);
        }

        // Filter by seller store_name (case-insensitive)
        if ($request->filled('store_name')) {
            $q = mb_strtolower($request->store_name);
            $query->whereHas('seller', function ($sq) use ($q) {
                $sq->whereRaw('LOWER(store_name) LIKE ?', ["%{$q}%"]);
            });
        }

        // Filter by seller province_id and city_id
        if ($request->filled('province_id')) {
            $query->whereHas('seller', function ($sq) use ($request) {
                $sq->where('province_id', $request->province_id);
            });
        }

        if ($request->filled('city_id')) {
            $query->whereHas('seller', function ($sq) use ($request) {
                $sq->where('city_id', $request->city_id);
            });
        }

        // Keyword search berdasarkan name, description, category (case-insensitive)
        if ($request->filled('q')) {
            $q = mb_strtolower($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->whereRaw('LOWER(name) LIKE ?', ["%{$q}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$q}%"])
                    ->orWhereRaw('LOWER(category) LIKE ?', ["%{$q}%"]);
            });
        }

        // Price filters
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        switch ($request->get('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;

            case 'newest':
            default:
            $query->orderBy('product_id', 'desc');
                break;
        }

        // Mengembalikan fields katalog publik yang sama dan paginasi seperti `index`
        $version = Cache::get('products_cache_version', 1);
        $paramsHash = md5(strtolower($request->fullUrl()));
        $cacheKey = "products:search:v{$version}:{$paramsHash}:page:{$request->get('page',1)}";

        $products = Cache::remember($cacheKey, 60, function () use ($query) {
            return $query->withAvg('reviews', 'rating')
            ->select(
                'product_id',
                'name',
                'slug',
                'price',
                'images',
                'created_at'
            )
            ->paginate(20);
        });

        // Normalize average rating into `average_rating` float
        $products->getCollection()->transform(function ($p) {
            $p->average_rating = $p->reviews_avg_rating ? round((float) $p->reviews_avg_rating, 2) : null;
            return $p;
        });

        return response()->json($products);
    }

}
