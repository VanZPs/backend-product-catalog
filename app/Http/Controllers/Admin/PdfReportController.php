<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfReportController extends Controller
{
    /**
     * (SRS-MartPlace-09) Admin Sellers Report by Status
     * Laporan Daftar Akun Penjual Berdasarkan Status
     */
    public function sellersByStatusReport(Request $request)
    {
        try {
            // Fetch sellers sorted by status (approved first, then others)
            $data = Seller::with('user:user_id,name')
                ->select('seller_id', 'user_id', 'store_name', 'status', 'pic_name')
                ->whereNull('deleted_at')
                ->orderByRaw("CASE WHEN status = 'approved' THEN 0 ELSE 1 END")
                ->orderBy('store_name')
                ->get();

            $pdf = Pdf::loadView('pdf.admin-sellers-by-status', [
                'data' => $data,
                'reportTitle' => 'LAPORAN DAFTAR AKUN PENJUAL BERDASARKAN STATUS',
                'reportDate' => now()->format('d-m-Y'),
            ])->setPaper('a4');

            return $pdf->download('laporan-penjual-status-' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            logger()->error('Sellers status report failed: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat laporan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * (SRS-MartPlace-10) Admin Sellers Report by Province
     * Laporan Daftar Toko Berdasarkan Lokasi Provinsi
     */
    public function sellersByProvinceReport(Request $request)
    {
        try {
            // Fetch sellers sorted by province
            $data = Seller::with(['user:user_id,name', 'province:code,name'])
                ->select('seller_id', 'user_id', 'store_name', 'province_id')
                ->where('status', 'approved')
                ->whereNull('deleted_at')
                ->orderBy('province_id')
                ->orderBy('store_name')
                ->get();

            $pdf = Pdf::loadView('pdf.admin-sellers-by-province-formal', [
                'data' => $data,
                'reportTitle' => 'LAPORAN DAFTAR TOKO BERDASARKAN LOKASI PROVINSI',
                'reportDate' => now()->format('d-m-Y'),
            ])->setPaper('a4');

            return $pdf->download('laporan-toko-provinsi-' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            logger()->error('Sellers province report failed: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat laporan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * (SRS-MartPlace-11) Admin Products Report by Rating
     * Laporan Daftar Produk Berdasarkan Rating
     */
    public function productsByRatingReport(Request $request)
    {
        try {
            // Fetch products with reviews and calculate rating
            $data = Product::with([
                'category:category_id,name',
                'seller:seller_id,store_name',
                'reviews' => function ($query) {
                    $query->with('province:code,name')
                        ->orderByDesc('created_at');
                }
            ])
                ->where('products.is_active', true)
                ->get()
                ->map(function ($product) {
                    // Calculate average rating
                    $product->avg_rating = $product->reviews->isEmpty() ? 0 : $product->reviews->avg('rating');
                    // Get latest review with province
                    $product->latest_review = $product->reviews->first();
                    return $product;
                })
                ->sortByDesc('avg_rating')
                ->values();

            $pdf = Pdf::loadView('pdf.admin-products-by-rating-formal', [
                'data' => $data,
                'reportTitle' => 'LAPORAN DAFTAR PRODUK BERDASARKAN RATING',
                'reportDate' => now()->format('d-m-Y'),
            ])->setPaper('a4');

            return $pdf->download('laporan-produk-rating-' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            logger()->error('Products rating report failed: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat laporan: ' . $e->getMessage()], 500);
        }
    }
}
