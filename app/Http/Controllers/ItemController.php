<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $items = Item::latest()->get();
        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'type' => 'required|in:lost,found',
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        Item::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'item_name' => $request->item_name,
            'category' => $request->category,
            'description' => $request->description,
            'location' => $request->location,
            'date' => $request->date,
            'image' => $imagePath,
        ]);

        return redirect()->route('items.index')->with('success', 'Item posted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
         return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}
