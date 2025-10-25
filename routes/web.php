<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicOccasionController;
use App\Http\Controllers\Admin\OccasionController as AdminOccasionController;
use App\Http\Controllers\Admin\ReservationController;


use App\Http\Controllers\BookingController;
;


// --- Publiek ---
Route::get('/', [PublicOccasionController::class, 'home'])->name('home');
Route::get('/occasions', [PublicOccasionController::class, 'index'])->name('occasions.index');
Route::get('/occasions/{slug}', [PublicOccasionController::class, 'show'])->name('occasions.show');

  // Contact
Route::post('contact', [ContactController::class, 'send'])->name('contact.send');

// --- Auth ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/reserveren', [BookingController::class, 'show'])->name('booking.show');          // ?type=aanhanger|stofzuiger
Route::get('/reserveren/slots', [BookingController::class, 'slots'])->name('booking.slots');  // AJAX: vrije tijden
Route::post('/reserveren', [BookingController::class, 'store'])->name('booking.store');

// --- Admin (achter login) ---
Route::middleware('auth')->prefix('admin')->as('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.occasions.index'));

    // Occasions CRUD
    Route::resource('occasions', AdminOccasionController::class);

    // Galerij acties (gebruik dezelfde alias!)
    Route::post('occasions/{occasion}/gallery',              [AdminOccasionController::class,'addGallery'])->name('occasions.gallery.add');
    Route::delete('occasions/{occasion}/gallery/{i}',        [AdminOccasionController::class,'removeGallery'])->name('occasions.gallery.remove');
    Route::post('occasions/{occasion}/gallery/{i}/cover',    [AdminOccasionController::class,'setCover'])->name('occasions.gallery.cover');

    // Aanhanger
    Route::get('/aanhanger', [ReservationController::class, 'index'])->name('aanhanger.index');
    Route::get('/aanhanger/create', [ReservationController::class, 'create'])->name('aanhanger.create');
    Route::post('/aanhanger', [ReservationController::class, 'store'])->name('aanhanger.store');
    Route::get('/aanhanger/{reservation}/edit', [ReservationController::class, 'edit'])->name('aanhanger.edit');
    Route::put('/aanhanger/{reservation}', [ReservationController::class, 'update'])->name('aanhanger.update');
    Route::delete('/aanhanger/{reservation}', [ReservationController::class, 'destroy'])->name('aanhanger.destroy');

    // Stofzuiger
    Route::get('/stofzuiger', [ReservationController::class, 'index'])->name('stofzuiger.index');
    Route::get('/stofzuiger/create', [ReservationController::class, 'create'])->name('stofzuiger.create');
    Route::post('/stofzuiger', [ReservationController::class, 'store'])->name('stofzuiger.store');
    Route::get('/stofzuiger/{reservation}/edit', [ReservationController::class, 'edit'])->name('stofzuiger.edit');
    Route::put('/stofzuiger/{reservation}', [ReservationController::class, 'update'])->name('stofzuiger.update');
    Route::delete('/stofzuiger/{reservation}', [ReservationController::class, 'destroy'])->name('stofzuiger.destroy');

    // Agenda
    Route::get('/agenda', [ReservationController::class, 'calendar'])->name('agenda.index');

  
});
