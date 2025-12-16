<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardAnalyticsController;
use App\Http\Controllers\CheckinController;
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

// Analytics Dashboard Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/analytics', [DashboardAnalyticsController::class, 'index'])
        ->name('analytics.dashboard');
});

// Check-in Routes (for organizers)
Route::middleware(['auth', 'verified', 'role:'.User::ROLE_ORGANIZER])->group(function () {
    Route::get('/event/{event}/checkin', [CheckinController::class, 'show'])
        ->name('checkin.show');
    Route::post('/event/{event}/checkin', [CheckinController::class, 'checkin'])
        ->name('checkin.checkin');
    Route::delete('/event/{event}/checkin/{attendeeId}', [CheckinController::class, 'undoCheckin'])
        ->name('checkin.undo');
    Route::get('/event/{event}/checkin/export', [CheckinController::class, 'exportCsv'])
        ->name('checkin.export-csv');
});

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

require __DIR__.'/auth.php';
