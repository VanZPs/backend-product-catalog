<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductPublicController extends Controller
{
    /**
     * Public catalog
     */
    public function index(Request $request)
    {
        $products = Product::select(
            'id',
            'name',
            'slug',
            'price',
            'images',
            'created_at'
        )
        ->orderBy('id', 'desc')
        ->paginate(20);

        return response()->json($products);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'id'          => $product->id,
            'name'        => $product->name,
            'slug'        => $product->slug,
            'description' => $product->description,
            'price'       => $product->price,
            'stock'       => $product->stock,
            'images'      => $product->images,
            'seller'      => [
                'id'          => $product->seller->id,
                'store_name'  => $product->seller->store_name,
                'city'        => $product->seller->city?->name,
                'province'    => $product->seller->province?->name,
            ],
            'created_at'  => $product->created_at,
        ]);
    }

}
