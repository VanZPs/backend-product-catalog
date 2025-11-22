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
}
