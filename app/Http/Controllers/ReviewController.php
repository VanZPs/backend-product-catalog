<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Provinsi;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
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
        $validated = $request->validate([
            'product_id'  => 'required|exists:products,id',
            'name'        => 'required|string|max:100',
            'email'       => 'required|email',
            'province_id' => 'required|exists:indonesia_provinces,id',
            'rating'      => 'required|integer|min:1|max:5',
            'comment'     => 'nullable|string|max:500',
        ]);

        $review = Review::create($validated);

        // Load product relation for snapshot and notify reviewer via email
        $review->load('product');
        try {
            Notification::route('mail', $review->email)
                ->notify(new ReviewThankYouNotification($review->toSnapshot()));
        } catch (\Throwable $e) {
            Log::error('Failed to send ReviewThankYouNotification', ['review_id' => $review->id, 'error' => $e->getMessage()]);
            // continue silently; review has been saved
        }

        return response()->json([
            'message' => 'Review submitted successfully',
            'review'  => $review
        ], 201);
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
