<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::get('/events/search', [EventController::class, 'search'])->name('events.search');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

Route::post('/events/{event}/announcements', [EventController::class, 'storeAnnouncement'])->name('events.announcements.store');
Route::delete('/events/{event}/announcements/{announcement}', [EventController::class, 'deleteAnnouncement'])->name('events.announcements.destroy');

