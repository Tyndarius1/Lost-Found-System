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
        //
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
        // Prevent claiming own item
        if ($item->user_id === Auth::id()) {
            return back()->with('error', 'You cannot claim your own item.');
        }

        // Prevent duplicate claims
        $existing = Claim::where('item_id', $item->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You already submitted a claim.');
        }

        $request->validate([
            'message' => 'required|min:10',
        ]);

        Claim::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        $item->update(['status' => 'claimed']);

        return back()->with('success', 'Claim submitted successfully.');
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
