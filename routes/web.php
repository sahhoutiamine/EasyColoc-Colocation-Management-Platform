<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ColocationController;
use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'banned'])->name('dashboard');

Route::middleware(['auth', 'banned'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('colocations', ColocationController::class);
    Route::post('colocations/{colocation}/leave', [ColocationController::class, 'leave'])->name('colocations.leave');
    Route::delete('colocations/{colocation}/members/{user}', [ColocationController::class, 'removeMember'])->name('colocations.removeMember');

    // Expenses & Settlements
    Route::get('colocations/{colocation}/expenses', [App\Http\Controllers\ExpenseController::class, 'index'])->name('colocations.expenses.index');
    Route::post('colocations/{colocation}/expenses', [App\Http\Controllers\ExpenseController::class, 'store'])->name('colocations.expenses.store');
    Route::delete('colocations/{colocation}/expenses/{expense}', [App\Http\Controllers\ExpenseController::class, 'destroy'])->name('colocations.expenses.destroy');
    Route::post('colocations/{colocation}/settlements/{settlement}/paid', [App\Http\Controllers\ExpenseController::class, 'markPaid'])->name('colocations.settlements.paid');

    // Invitations
    Route::get('colocations/{colocation}/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
    Route::post('colocations/{colocation}/invitations', [InvitationController::class, 'store'])->name('invitations.store');
    Route::get('invitations/{token}', [InvitationController::class, 'show'])->name('invitations.show');
    Route::post('invitations/{token}/accept', [InvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('invitations/{token}/refuse', [InvitationController::class, 'refuse'])->name('invitations.refuse');

    // Admin
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/admin/users/{user}/ban', [App\Http\Controllers\AdminController::class, 'toggleBan'])->name('admin.users.ban');
        
        Route::get('/admin/categories', [App\Http\Controllers\AdminController::class, 'categories'])->name('admin.categories');
        Route::post('/admin/categories', [App\Http\Controllers\AdminController::class, 'storeCategory'])->name('admin.categories.store');
        Route::delete('/admin/categories/{category}', [App\Http\Controllers\AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');
    });
});

require __DIR__.'/auth.php';
