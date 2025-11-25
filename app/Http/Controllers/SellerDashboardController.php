<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerDashboardController extends Controller
{
    public function overview(Request $request)
    {
        $seller = Seller::where('user_id', $request->user()->user_id)->firstOrFail();

        // 1) Jumlah produk
        $productCount = Product::where('seller_id', $seller->seller_id)->count();

        // 2) Jumlah review untuk semua produk seller
        $reviewCount = Review::whereIn(
            'product_id',
            Product::where('seller_id', $seller->seller_id)->pluck('product_id')
        )->count();

        // 3) Rata-rata rating
        $averageRating = Review::whereIn(
            'product_id',
            Product::where('seller_id', $seller->seller_id)->pluck('product_id')
        )->avg('rating');

        $averageRating = round($averageRating ?? 0, 2);

        // 4) Persentase review positif (rating >= 4)
        $positiveReviews = Review::whereIn(
            'product_id',
            Product::where('seller_id', $seller->seller_id)->pluck('product_id')
        )->where('rating', '>=', 4)->count();

        $positivePercentage = $reviewCount > 0
            ? round(($positiveReviews / $reviewCount) * 100, 2)
            : 0;

        // 5) Produk terbaru
        $latestProducts = Product::where('seller_id', $seller->seller_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get(['product_id', 'name', 'slug', 'created_at']);

        // 6) Produk terpopuler berdasarkan views (dummy MVP)
        $totalVisitors = Product::where('seller_id', $seller->seller_id)->sum('visitor');
        $topViewed = Product::where('seller_id', $seller->seller_id)
            ->orderBy('visitor', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'product_count'       => $productCount,
            'review_count'        => $reviewCount,
            'average_rating'      => $averageRating,
            'positive_percentage' => $positivePercentage,
            'total_visitors'      => $totalVisitors,
            'latest_products'     => $latestProducts,
        ]);
    }

    public function stockPerProduct(Request $request)
    {
        $seller = Seller::where('user_id', $request->user()->user_id)->firstOrFail();

        $data = Product::where('seller_id', $seller->seller_id)
            ->select('product_id', 'name', 'stock')
            ->get();

        return response()->json($data);
    }

    public function ratingPerProduct(Request $request)
    {
        $seller = Seller::where('user_id', $request->user()->user_id)->firstOrFail();

        $productIds = Product::where('seller_id', $seller->seller_id)->pluck('product_id');

        $data = Review::whereIn('product_id', $productIds)
            ->select('product_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as total_reviews'))
            ->groupBy('product_id')
            ->get();

        return response()->json($data);
    }

    public function reviewersByProvince(Request $request)
    {
        $seller = Seller::where('user_id', $request->user()->user_id)->firstOrFail();

        $productIds = Product::where('seller_id', $seller->seller_id)->pluck('product_id');

        $data = Review::whereIn('product_id', $productIds)
            ->select('province_id', DB::raw('COUNT(*) as total'))
            ->groupBy('province_id')
            ->get();

        return response()->json($data);
    }
}
