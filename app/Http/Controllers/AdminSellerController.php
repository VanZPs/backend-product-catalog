<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Notifications\SellerApproved;
use App\Notifications\SellerRejected;
use Illuminate\Http\Request;

class AdminSellerController extends Controller
{
    // List menunggu verifikasi
    public function pending()
    {
        return Seller::where('status', 'pending')->with('user:id,name,email')->get();
    }

    // Detail seller
    public function show($id)
    {
        return Seller::with('user:id,name,email')->findOrFail($id);
    }

    // Approve seller → kirim email
    public function approve($id)
    {
        $seller = Seller::findOrFail($id);

        $seller->update([
            'status' => 'approved',
            'verified_at' => now(),
        ]);

        $seller->user->notify(new SellerApproved($seller));

        return response()->json(['message' => 'Seller approved']);
    }

    // Reject seller → hapus data seller → kirim email
    public function reject(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);

        $seller->user->notify(new SellerRejected($seller, $request->reason ?? null));

        $seller->delete();

        return response()->json(['message' => 'Seller rejected & deleted']);
    }
}
