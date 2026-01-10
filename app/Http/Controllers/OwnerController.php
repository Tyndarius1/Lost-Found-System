<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\Item;

class OwnerController extends Controller
{
    // Show all claims for items owned by logged-in user
    public function index()
    {
        $claims = Claim::whereHas('item', function($q) {
            $q->where('user_id', auth()->id());
        })->latest()->get();

        return view('owner.claims', compact('claims'));
    }

    // Approve or reject claim
    public function update(Request $request, Claim $claim)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        // Ensure owner is updating their own item claim
        if ($claim->item->user_id !== auth()->id()) {
            abort(403);
        }

        $claim->update(['status' => $request->status]);

        // Update item status if approved
        if ($request->status === 'approved') {
            $claim->item->update(['status' => 'returned']);
        } else {
            $claim->item->update(['status' => 'open']);
        }

        return back()->with('success', 'Claim status updated.');
    }
}
