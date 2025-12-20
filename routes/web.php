<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardAnalyticsController;
use App\Http\Controllers\CheckinController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// ---------------------------
// Guest & Auth Redirects
// ---------------------------
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ---------------------------
// Public Event Routes
// ---------------------------
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// ---------------------------
// Authenticated User Routes
// ---------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    // -----------------------
    // Profile
    // -----------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // -----------------------
    // Analytics Dashboard
    // -----------------------
    Route::get('/analytics', [DashboardAnalyticsController::class, 'index'])
        ->name('analytics.dashboard');

    // -----------------------
    // Registration & Ticketing
    // -----------------------
    Route::post('/events/{event}/register', [RegistrationController::class, 'register'])
        ->name('registrations.store');

    Route::get('/my-registrations', [RegistrationController::class, 'myRegistrations'])
        ->name('registrations.myRegistrations');

    Route::get('/registrations/{registration}', [RegistrationController::class, 'show'])
        ->name('registrations.show');

    Route::get('/registrations/{registration}/cancel', [RegistrationController::class, 'confirmCancel'])
        ->name('registrations.confirmCancel');

    Route::post('/registrations/{registration}/cancel', [RegistrationController::class, 'cancel'])
        ->name('registrations.cancel');

    Route::get('/registrations/{registration}/ticket/download', [RegistrationController::class, 'downloadTicket'])
        ->name('registrations.downloadTicket');

    // -----------------------
    // Notifications
    // -----------------------
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])
        ->name('notifications.readAll');

    // -----------------------
    // Sessions (Event Schedule)
    // -----------------------
    Route::prefix('events/{event}/sessions')->group(function () {
        Route::get('/create', [\App\Http\Controllers\SessionController::class, 'create'])
            ->name('events.sessions.create');
        Route::post('/', [\App\Http\Controllers\SessionController::class, 'store'])
            ->name('events.sessions.store');
        Route::get('/{session}/edit', [\App\Http\Controllers\SessionController::class, 'edit'])
            ->name('events.sessions.edit');
        Route::patch('/{session}', [\App\Http\Controllers\SessionController::class, 'update'])
            ->name('events.sessions.update');
        Route::delete('/{session}', [\App\Http\Controllers\SessionController::class, 'destroy'])
            ->name('events.sessions.destroy');
    });
});

// ---------------------------
// Organizer Routes
// ---------------------------
Route::middleware(['auth', 'verified', 'role:' . User::ROLE_ORGANIZER])->group(function () {

    // Organizer hub
    Route::get('/organizer/hub', function () {
        return view('roles.organizer');
    })->name('organizer.hub');

    // Event management
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::patch('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    // Speakers management
    Route::prefix('speakers')->group(function () {
        Route::get('/', [\App\Http\Controllers\SpeakerController::class, 'index'])->name('speakers.index');
        Route::get('/create', [\App\Http\Controllers\SpeakerController::class, 'create'])->name('speakers.create');
        Route::post('/', [\App\Http\Controllers\SpeakerController::class, 'store'])->name('speakers.store');
        Route::get('/{speaker}/edit', [\App\Http\Controllers\SpeakerController::class, 'edit'])->name('speakers.edit');
        Route::patch('/{speaker}', [\App\Http\Controllers\SpeakerController::class, 'update'])->name('speakers.update');
        Route::delete('/{speaker}', [\App\Http\Controllers\SpeakerController::class, 'destroy'])->name('speakers.destroy');
    });

    // Check-in routes
    Route::prefix('event/{event}/checkin')->group(function () {
        Route::get('/', [CheckinController::class, 'show'])->name('checkin.show');
        Route::post('/', [CheckinController::class, 'checkin'])->name('checkin.checkin');
        Route::delete('/{attendeeId}', [CheckinController::class, 'undoCheckin'])->name('checkin.undo');
        Route::get('/export', [CheckinController::class, 'exportCsv'])->name('checkin.export-csv');
    });
});

// ---------------------------
// Admin Routes
// ---------------------------
Route::middleware(['auth', 'verified', 'role:' . User::ROLE_ADMIN])->group(function () {
    Route::view('/admin/overview', 'roles.admin')->name('admin.overview');
});

// ---------------------------
// Attendee Routes
// ---------------------------
Route::middleware(['auth', 'verified', 'role:' . User::ROLE_ATTENDEE])->group(function () {
    Route::view('/attendee/space', 'roles.attendee')->name('attendee.space');
});

// ---------------------------
// Auth Routes
// ---------------------------
require __DIR__ . '/auth.php';
