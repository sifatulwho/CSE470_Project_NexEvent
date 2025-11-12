<?php

use App\Http\Controllers\Features\EventController;
use App\Http\Controllers\Features\EventResourceController;
use App\Http\Controllers\Features\WishlistController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:'.User::ROLE_ADMIN])->group(function () {
    Route::view('/admin/overview', 'roles.admin')->name('admin.overview');
});

Route::middleware(['auth', 'verified', 'role:'.User::ROLE_ORGANIZER])->group(function () {
    Route::view('/organizer/hub', 'roles.organizer')->name('organizer.hub');
});

Route::middleware(['auth', 'verified', 'role:'.User::ROLE_ATTENDEE])->group(function () {
    Route::view('/attendee/space', 'roles.attendee')->name('attendee.space');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Features Routes
Route::prefix('features')->name('features.')->group(function () {
    // Events
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

    // Resources (Organizers only)
    Route::middleware('auth')->group(function () {
        Route::get('/events/{event}/resources', [EventResourceController::class, 'index'])->name('resources.index');
        Route::get('/events/{event}/resources/create', [EventResourceController::class, 'create'])->name('resources.create');
        Route::post('/events/{event}/resources', [EventResourceController::class, 'store'])->name('resources.store');
        Route::get('/resources/{eventResource}/download', [EventResourceController::class, 'download'])->name('resources.download');
        Route::delete('/resources/{eventResource}', [EventResourceController::class, 'destroy'])->name('resources.destroy');

        // Wishlist
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
        Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.remove');
    });
});

require __DIR__.'/auth.php';
