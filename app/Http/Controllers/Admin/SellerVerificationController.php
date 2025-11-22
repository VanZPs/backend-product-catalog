<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Notifications\SellerApproved;
use App\Notifications\SellerRejected;
use Illuminate\Http\Request;

class SellerVerificationController extends Controller
{
    public function approve($id)
    {
        $seller = Seller::findOrFail($id);

        $seller->update([
            'status' => 'approved',
            'verified_at' => now()
        ]);

        $seller->user->notify(new SellerApproved($seller));

        return response()->json(['message' => 'Seller approved & notification sent.']);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:255'
        ]);

        $seller = Seller::findOrFail($id);

        // kirim email dulu
        $seller->user->notify(new SellerRejected($seller, $request->reason));

        // lalu hapus seller
        $seller->delete();

        return response()->json(['message' => 'Seller rejected & deleted. Notification sent.']);
    }
}
