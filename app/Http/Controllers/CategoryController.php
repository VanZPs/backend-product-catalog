<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Return a list of all categories.
     */
    public function index(Request $request)
    {
        $categories = Category::select('category_id', 'name', 'slug', 'description')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $categories]);
    }
}

