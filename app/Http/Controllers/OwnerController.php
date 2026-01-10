<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

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
    if ($claim->item->user_id !== Auth::id()) {
        abort(403);
    }

    $request->validate(['status' => 'required|in:approved,rejected']);
    $claim->update(['status' => $request->status]);

    // Create a notification for the claimant
    \App\Models\Notification::create([
        'user_id' => $claim->user_id,
        'title' => 'Claim Update',
        'message' => "Your claim for '{$claim->item->item_name}' has been " . strtoupper($request->status) . ".",
    ]);

    if ($request->status === 'approved') {
        $claim->item->update(['status' => 'resolved']);
    }

    return back()->with('success', 'Claim ' . $request->status . ' and user notified!');
}
}
