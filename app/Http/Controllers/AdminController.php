<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\Claim;

class AdminController extends Controller
{
      public function dashboard() {
        $usersCount = User::count();
        $itemsCount = Item::count();
        $claimsCount = Claim::count();

        return view('admin.dashboard', compact('usersCount','itemsCount','claimsCount'));
    }

    public function items() {
        $items = Item::latest()->get();
        return view('admin.items', compact('items'));
    }

    public function claims() {
        $claims = Claim::latest()->get();
        return view('admin.claims', compact('claims'));
    }

    public function users() {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }

    public function updateClaim(Request $request, Claim $claim)
{
    $request->validate([
        'status' => 'required|in:approved,rejected'
    ]);

    $claim->update(['status' => $request->status]);

    // Update item status if approved
    if($request->status === 'approved'){
        $claim->item->update(['status' => 'returned']);
    } else {
        $claim->item->update(['status' => 'open']);
    }

    return back()->with('success', 'Claim status updated.');
}

public function deleteItem(Item $item)
{
    $item->delete();
    return back()->with('success', 'Item deleted successfully.');
}

public function editItem(Item $item)
{
    return view('admin.edit-item', compact('item'));
}

public function updateItem(Request $request, Item $item)
{
    $request->validate([
        'type' => 'required|in:lost,found',
        'item_name' => 'required|string|max:255',
        'category' => 'required|string|max:255',
        'description' => 'nullable|string',
        'location' => 'required|string|max:255',
        'date' => 'required|date',
        'image' => 'nullable|image|max:2048', // optional
    ]);

    $data = $request->only(['type', 'item_name', 'category', 'description', 'location', 'date']);

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('items', 'public');
    }

    $item->update($data);

    return redirect()->route('admin.items')->with('success', 'Item updated successfully.');
}

}
