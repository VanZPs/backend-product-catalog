<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Provinsi;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use App\Notifications\ReviewThankYouNotification;

class ReviewController extends Controller
{
    /**
     * PUBLIC — get review list for a product by slug
     */
    public function list($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $reviews = $product->reviews()
            ->with('province:id,name')
            ->latest()
            ->get();

        return response()->json($reviews);
    }

    /**
     * PUBLIC — create review (no login)
     */
    public function store(Request $request)
    {
        $rules = [
            'product_id'  => 'required|exists:products,product_id',
            'name'        => 'required|string|max:100',
            'email'       => ['required','email'],
            'province_id' => 'required|exists:indonesia_provinces,code',
            'rating'      => 'required|integer|min:1|max:5',
            'comment'     => 'nullable|string|max:500',
        ];

        // Enforce uniqueness per product for email and phone (if present)
        $productId = $request->input('product_id');

        $rules['email'][] = Rule::unique('reviews')->where(function ($query) use ($productId) {
            return $query->where('product_id', $productId);
        });

        if (Schema::hasColumn('reviews', 'phone')) {
            $rules['phone'] = [
                'nullable',
                'string',
                'max:32',
                Rule::unique('reviews')->where(function ($query) use ($productId) {
                    return $query->where('product_id', $productId);
                }),
            ];
        }

        $validated = $request->validate($rules);

        $review = Review::create($validated);

        // Load product relation for snapshot and notify reviewer via email
        $review->load('product');
        try {
            Notification::route('mail', $review->email)
                ->notify(new ReviewThankYouNotification($review->toSnapshot()));
        } catch (\Throwable $e) {
            Log::error('Failed to send ReviewThankYouNotification', ['review_id' => $review->review_id ?? null, 'error' => $e->getMessage()]);
            // continue silently; review has been saved
        }

        // Return the review as top-level JSON (tests expect keys like review_id at root)
        return response()->json($review, 201);
    }

    /**
     * ADMIN/OWNER only? (Nanti kita sesuaikan)
     * For now disabled (public system does NOT delete)
     */
    public function destroy(Review $review)
    {
        return response()->json([
            'message' => 'Review deletion is not available in public mode'
        ], 403);
    }
}
