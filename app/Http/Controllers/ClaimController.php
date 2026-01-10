<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      // 1. Fetch the items posted by the LOGGED-IN user
        $recentActivity = Item::where('user_id', Auth::id())
            ->latest()            // Get newest first
            ->take(5)             // Limit to 5 results
            ->get();

        // 2. Pass the variable to the view
        return view('home', compact('recentActivity'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Item $item)
{
    // 1. Prevent claiming own item
    if ($item->user_id === Auth::id()) {
        return back()->with('error', 'You cannot claim your own item.');
    }

    // 2. Prevent duplicate claims by the SAME person
    $existing = Claim::where('item_id', $item->id)
        ->where('user_id', Auth::id())
        ->first();

    if ($existing) {
        return back()->with('error', 'You already submitted a claim for this item.');
    }

    $request->validate([
        'message' => 'required|min:10',
    ]);

    // 3. Create the claim
    Claim::create([
        'item_id' => $item->id,
        'user_id' => Auth::id(),
        'message' => $request->message,
        'status' => 'pending', // Ensure your claims table has a status column
    ]);

    // IMPORTANT: We removed $item->update(['status' => 'claimed']);
    // This allows the item to stay visible for other potential owners.

    return back()->with('success', 'Claim submitted! The finder will review your message.');
}

    /**
     * Display the specified resource.
     */
    public function show(Claim $claim)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Claim $claim)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Claim $claim)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Claim $claim)
    {
        //
    }
}
