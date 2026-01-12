<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\Claim;
use Illuminate\Support\Facades\Hash;

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
    // Adding with() ensures we get the related data efficiently
    $claims = Claim::with(['item', 'user'])->latest()->get();
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



public function storeUser(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:user,admin',
        'age' => 'nullable|integer',
        'phone' => 'nullable|string|max:20',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role,
        'age' => $request->age,
        'phone' => $request->phone,
    ]);

    return back()->with('success', 'User created successfully.');
}

public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:user,admin',
            'age'   => 'nullable|integer',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->fill($request->only(['name', 'email', 'role', 'age', 'phone']));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user) 
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete yourself!');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
    
    // Remember to include your items, dashboard, and claims methods here too

}
