<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardAnalyticsController;
use App\Http\Controllers\CheckinController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Redirect guests to the login page; authenticated users go to the dashboard
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public events routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
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

// Organizer events management routes
Route::middleware(['auth', 'verified', 'role:'.User::ROLE_ORGANIZER])->group(function () {
    Route::get('/organizer/hub', function () {
        return view('roles.organizer');
    })->name('organizer.hub');
    
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::patch('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    // Speakers management
    Route::get('/speakers', [\App\Http\Controllers\SpeakerController::class, 'index'])->name('speakers.index');
    Route::get('/speakers/create', [\App\Http\Controllers\SpeakerController::class, 'create'])->name('speakers.create');
    Route::post('/speakers', [\App\Http\Controllers\SpeakerController::class, 'store'])->name('speakers.store');
    Route::get('/speakers/{speaker}/edit', [\App\Http\Controllers\SpeakerController::class, 'edit'])->name('speakers.edit');
    Route::patch('/speakers/{speaker}', [\App\Http\Controllers\SpeakerController::class, 'update'])->name('speakers.update');
    Route::delete('/speakers/{speaker}', [\App\Http\Controllers\SpeakerController::class, 'destroy'])->name('speakers.destroy');
});

// Registration and ticket routes (for authenticated users)
Route::middleware(['auth', 'verified'])->group(function () {
    // Sessions (Event schedule) - rely on policy to authorize (organizer/admin)
    Route::get('/events/{event}/sessions/create', [\App\Http\Controllers\SessionController::class, 'create'])->name('events.sessions.create');
    Route::post('/events/{event}/sessions', [\App\Http\Controllers\SessionController::class, 'store'])->name('events.sessions.store');
    Route::get('/events/{event}/sessions/{session}/edit', [\App\Http\Controllers\SessionController::class, 'edit'])->name('events.sessions.edit');
    Route::patch('/events/{event}/sessions/{session}', [\App\Http\Controllers\SessionController::class, 'update'])->name('events.sessions.update');
    Route::delete('/events/{event}/sessions/{session}', [\App\Http\Controllers\SessionController::class, 'destroy'])->name('events.sessions.destroy');
    Route::post('/events/{event}/register', [RegistrationController::class, 'register'])->name('registrations.store');
    Route::get('/my-registrations', [RegistrationController::class, 'myRegistrations'])->name('registrations.myRegistrations');
    Route::get('/registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
    Route::get('/registrations/{registration}/cancel', [RegistrationController::class, 'confirmCancel'])->name('registrations.confirmCancel');
    Route::post('/registrations/{registration}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');
    Route::get('/registrations/{registration}/ticket/download', [RegistrationController::class, 'downloadTicket'])->name('registrations.downloadTicket');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});

// Admin routes
Route::middleware(['auth', 'verified', 'role:'.User::ROLE_ADMIN])->group(function () {
    Route::view('/admin/overview', 'roles.admin')->name('admin.overview');
});

// Attendee routes
Route::middleware(['auth', 'verified', 'role:'.User::ROLE_ATTENDEE])->group(function () {
    Route::view('/attendee/space', 'roles.attendee')->name('attendee.space');
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
