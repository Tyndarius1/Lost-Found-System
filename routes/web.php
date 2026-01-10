<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;

Route::get('/', function () {
    return view('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::middleware(['auth'])->group(function () {
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
});


Route::post('/items/{item}/claim', [ClaimController::class, 'store'])
    ->name('items.claim')
    ->middleware('auth');


Route::middleware(['auth'])->group(function () {
    Route::get('/my-claims', [OwnerController::class, 'index'])->name('owner.claims');
    Route::post('/claims/{claim}/update', [OwnerController::class, 'update'])->name('owner.claims.update');
});




// Admin Routes — only accessible by admin users
Route::middleware(['auth', AdminMiddleware::class])->group(function () {

    // --- Dashboard ---
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

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