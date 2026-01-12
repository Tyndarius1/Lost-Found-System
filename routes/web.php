<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FunController;

Route::get('/', function () {
    if (Auth::check()) {
        // If the user is an admin, send them to /admin
        if (Auth::user()->is_admin) { // Adjust 'is_admin' to your actual column name
            return redirect('/admin');
        }
        // Otherwise, send them to /home
        return redirect('/home');
    }
    
    // If not logged in at all, show the landing page or login
    return view('login'); 
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::middleware(['auth'])->group(function () {
   Route::get('/fun', [FunController::class, 'index'])->name('fun.index')->middleware('auth');
    Route::post('/notifications/read', function() {
    Auth::user()->notifications()->update(['is_read' => true]);
    return back();
})->name('notifications.read');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/items/user', [ItemController::class, 'userItems'])->name('items.user')->middleware('auth');
    // Add this inside your auth middleware group
    Route::get('/my-items', [ItemController::class, 'userItems'])->name('items.user');
    Route::get('/home', [ClaimController::class, 'index'])->name('home')->middleware('auth');
    Route::get('/admin', [ItemController::class, 'recent'])->name('admin.dashboard');
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
});





Route::middleware(['auth'])->group(function () {
    Route::post('/items/{item}/claim', [ClaimController::class, 'store'])
    ->name('items.claim')
    ->middleware('auth');
    Route::get('/my-claims', [OwnerController::class, 'index'])->name('owner.claims');
    Route::post('/claims/{claim}/update', [OwnerController::class, 'update'])->name('owner.claims.update');
});




// Admin Routes — only accessible by admin users
Route::middleware(['auth', AdminMiddleware::class])->group(function () {

    // --- Dashboard ---
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');


    // --- Items ---
    Route::get('/admin/items', [AdminController::class, 'items'])->name('admin.items');
    Route::get('/admin/items/{item}/edit', [AdminController::class, 'editItem'])->name('admin.items.edit');
    Route::put('/admin/items/{item}', [AdminController::class, 'updateItem'])->name('admin.items.update');
    Route::delete('/admin/items/{item}', [AdminController::class, 'deleteItem'])->name('admin.items.delete');

    // --- Claims ---
    Route::get('/admin/claims', [AdminController::class, 'claims'])->name('admin.claims');
    Route::post('/admin/claims/{claim}/update', [AdminController::class, 'updateClaim'])->name('admin.claims.update');

    // --- Users ---
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
});