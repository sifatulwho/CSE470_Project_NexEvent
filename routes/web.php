<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardAnalyticsController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\EventResourceController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CertificateController;
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
// Search Routes (Public)
// ---------------------------
Route::get('/search', [SearchController::class, 'search'])->name('search');

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

    // -----------------------
    // Wishlist
    // -----------------------
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/events/{event}/wishlist', [WishlistController::class, 'addEvent'])->name('wishlist.add-event');
    Route::post('/resources/{resource}/wishlist', [WishlistController::class, 'addResource'])->name('wishlist.add-resource');
    Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // -----------------------
    // Comments
    // -----------------------
    Route::post('/events/{event}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::patch('/events/{event}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/events/{event}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // -----------------------
    // Reviews
    // -----------------------
    Route::post('/events/{event}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/events/{event}/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/events/{event}/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // -----------------------
    // Messages/Chat
    // -----------------------
    Route::get('/messages', [MessageController::class, 'conversations'])->name('messages.conversations');
    Route::get('/messages/{user}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages/{user}', [MessageController::class, 'storeIndividual'])->name('messages.store-individual');

    // -----------------------
    // Certificates
    // -----------------------
    Route::post('/events/{event}/certificates/generate', [CertificateController::class, 'generate'])->name('certificates.generate');
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
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

    // -----------------------
    // Announcements
    // -----------------------
    Route::prefix('events/{event}/announcements')->group(function () {
        Route::get('/', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('/create', [AnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('/', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::patch('/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });

    // -----------------------
    // Event Resources
    // -----------------------
    Route::prefix('events/{event}/resources')->group(function () {
        Route::get('/', [EventResourceController::class, 'index'])->name('events.resources.index');
        Route::get('/create', [EventResourceController::class, 'create'])->name('events.resources.create');
        Route::post('/', [EventResourceController::class, 'store'])->name('events.resources.store');
        Route::get('/{resource}', [EventResourceController::class, 'show'])->name('events.resources.show');
        Route::get('/{resource}/download', [EventResourceController::class, 'download'])->name('events.resources.download');
        Route::delete('/{resource}', [EventResourceController::class, 'destroy'])->name('events.resources.destroy');
    });

    // -----------------------
    // Group Chat (Event Messages)
    // -----------------------
    Route::prefix('events/{event}/messages')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('events.messages.index');
        Route::post('/', [MessageController::class, 'storeGroup'])->name('events.messages.store');
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
