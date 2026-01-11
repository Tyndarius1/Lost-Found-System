<?php 


namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Claim;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Fetch latest 5 items
        $items = Item::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'Item',
                    'user' => $item->user->name,
                    'action' => 'Added new ' . $item->item_name,
                    'date' => $item->created_at->diffForHumans(),
                    'status' => $item->status == 'open' ? 'Open' : ucfirst($item->status)
                ];
            });

        // Fetch latest 5 claims
        $claims = Claim::with('user', 'item')
            ->latest()
            ->take(5)
            ->get()
            ->map(function($claim) {
                return [
                    'type' => 'Claim',
                    'user' => $claim->user->name,
                    'action' => 'Submitted claim for ' . $claim->item->item_name,
                    'date' => $claim->created_at->diffForHumans(),
                    'status' => ucfirst($claim->status)
                ];
            });

        // Merge and sort
        $recentActivity = $items->merge($claims)->sortByDesc('date')->take(5);

        // Also pass counts for the dashboard cards
        $usersCount = \App\Models\User::count();
        $itemsCount = Item::count();
        $claimsCount = Claim::where('status', 'pending')->count();

        return view('admin.dashboard', compact('recentActivity', 'usersCount', 'itemsCount', 'claimsCount'));
    }
}
