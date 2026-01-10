<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Define school categories and locations once to use across all methods.
     */
    private function getSchoolData()
    {
        return [
            'categories' => [
                'Electronics', 'Documents/IDs', 'Wallets/Currency', 
                'Books/Stationary', 'Clothing', 'Keys', 'Bags', 
                'Jewelry/Accessories', 'Others'
            ],
            'locations' => [
                'Classrooms (Level 1)' => ['Room 101', 'Room 102', 'Room 103', 'Room 104', 'Room 105', 'Room 106'],
                'Classrooms (Level 2)' => ['Room 201', 'Room 202', 'Room 203', 'Room 204', 'Room 205', 'Room 206'],
                'Offices' => ['Admin Office', 'Registrar', 'Cashier', 'Dean\'s Office', 'Faculty Room'],
                'Facilities' => ['Clinic', 'Library', 'Canteen', 'Gymnasium', 'Computer Lab', 'Science Lab', 'Chapel'],
                'Common Areas' => ['Main Gate', 'Parking Lot', 'Student Lounge', 'Hallway']
            ]
        ];
    }

    public function index()
    {
        $items = Item::with('user')->latest()->paginate(9);
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $data = $this->getSchoolData();
        return view('items.create', [
            'categories' => $data['categories'],
            'locations' => $data['locations']
        ]);
    }

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
            'status' => 'open', // Ensure new items start as open
        ]);

        return redirect()->route('items.index')->with('success', 'Item posted successfully.');
    }

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        if (auth()->id() !== $item->user_id) { abort(403); }

        $data = $this->getSchoolData();
        return view('items.edit', [
            'item' => $item,
            'categories' => $data['categories'],
            'locations' => $data['locations']
        ]);
    }

    public function update(Request $request, Item $item)
    {
        if (auth()->id() !== $item->user_id) { abort(403, 'Unauthorized action.'); }

        $request->validate([
            'type' => 'required|in:lost,found',
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->route('items.user')->with('success', 'Item updated successfully!');
    }

    public function destroy(Item $item)
    {
        if (auth()->id() !== $item->user_id) { abort(403); }

        if ($item->image) { Storage::disk('public')->delete($item->image); }
        $item->delete();

        return redirect()->route('items.user')->with('success', 'Item deleted successfully!');
    }

    public function userItems()
    {
        $items = Item::where('user_id', auth()->id())->latest()->paginate(10);
        return view('items.my_items', compact('items'));
    }
}