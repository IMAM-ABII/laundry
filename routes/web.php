<?php

use App\Http\Controllers\LaundryItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [OrderController::class, 'index'])->name('customer.index');
Route::post('/checkout', [OrderController::class, 'store'])->name('customer.checkout');

Route::get('/dashboard', function (Request $request) {
    if (! in_array($request->user()->role, ['admin', 'kasir'], true)) {
        return redirect()->route('customer.index');
    }

    return app(OrderController::class)->adminDashboard();
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'staff'])->group(function () {
    Route::get('/admin/dashboard', [OrderController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::patch('/admin/orders/{id}/status', [OrderController::class, 'updateStatus'])
        ->name('admin.orders.updateStatus');
    Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy'])
        ->name('admin.orders.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('/admin/laundry_items', LaundryItemController::class)
        ->except(['show'])
        ->names('laundry_items');
});

require __DIR__.'/auth.php';
