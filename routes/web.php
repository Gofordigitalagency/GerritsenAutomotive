<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicOccasionController;
use App\Http\Controllers\admin\OccasionController as AdminOccasionController;
use App\Http\Controllers\admin\ReservationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SellCarController;


// --- Publiek ---
Route::get('/', [PublicOccasionController::class, 'home'])->name('home');
Route::get('/occasions', [PublicOccasionController::class, 'index'])->name('occasions.index');
Route::get('/occasions/{slug}', [PublicOccasionController::class, 'show'])->name('occasions.show');

// Contact
Route::post('contact', [ContactController::class, 'send'])->name('contact.send');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// --- Auth ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::post('/sell-car', [SellCarController::class, 'store'])
    ->name('sellcar.store');   // ⬅️ deze naam gebruikt je form

// Reserveren (publiek)
Route::get('/reserveren', [BookingController::class, 'show'])->name('booking.show');          // ?type=aanhanger|stofzuiger|koplampen
Route::get('/reserveren/slots', [BookingController::class, 'slots'])->name('booking.slots');  // AJAX: vrije tijden
Route::post('/reserveren', [BookingController::class, 'store'])->name('booking.store');

// --- Admin (achter login) ---
Route::middleware('auth')->prefix('admin')->as('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.occasions.index'));

    // Occasions CRUD
    Route::resource('occasions', AdminOccasionController::class);
Route::get('occasions/rdw/{kenteken}', [AdminOccasionController::class, 'rdwLookup'])
    ->name('occasions.rdw');

    // Galerij acties
    Route::post('occasions/{occasion}/gallery',           [AdminOccasionController::class,'addGallery'])->name('occasions.gallery.add');
    Route::delete('occasions/{occasion}/gallery/{i}',     [AdminOccasionController::class,'removeGallery'])->name('occasions.gallery.remove');
    Route::post('occasions/{occasion}/gallery/{i}/cover', [AdminOccasionController::class,'setCover'])->name('occasions.gallery.cover');

    // Aanhanger
    Route::get('/aanhanger',                    [ReservationController::class, 'index'])->name('aanhanger.index');
    Route::get('/aanhanger/create',             [ReservationController::class, 'create'])->name('aanhanger.create');
    Route::post('/aanhanger',                   [ReservationController::class, 'store'])->name('aanhanger.store');
    Route::get('/aanhanger/{reservation}/edit', [ReservationController::class, 'edit'])->name('aanhanger.edit');
    Route::put('/aanhanger/{reservation}',      [ReservationController::class, 'update'])->name('aanhanger.update');
    Route::delete('/aanhanger/{reservation}',   [ReservationController::class, 'destroy'])->name('aanhanger.destroy');

    // Stofzuiger
    Route::get('/stofzuiger',                    [ReservationController::class, 'index'])->name('stofzuiger.index');
    Route::get('/stofzuiger/create',             [ReservationController::class, 'create'])->name('stofzuiger.create');
    Route::post('/stofzuiger',                   [ReservationController::class, 'store'])->name('stofzuiger.store');
    Route::get('/stofzuiger/{reservation}/edit', [ReservationController::class, 'edit'])->name('stofzuiger.edit');
    Route::put('/stofzuiger/{reservation}',      [ReservationController::class, 'update'])->name('stofzuiger.update');
    Route::delete('/stofzuiger/{reservation}',   [ReservationController::class, 'destroy'])->name('stofzuiger.destroy');

    // ✅ Koplampen
    Route::get('/koplampen',                    [ReservationController::class, 'index'])->name('koplampen.index');
    Route::get('/koplampen/create',             [ReservationController::class, 'create'])->name('koplampen.create');
    Route::post('/koplampen',                   [ReservationController::class, 'store'])->name('koplampen.store');
    Route::get('/koplampen/{reservation}/edit', [ReservationController::class, 'edit'])->name('koplampen.edit');
    Route::put('/koplampen/{reservation}',      [ReservationController::class, 'update'])->name('koplampen.update');
    Route::delete('/koplampen/{reservation}',   [ReservationController::class, 'destroy'])->name('koplampen.destroy');

    // Agenda
    Route::get('/agenda', [ReservationController::class, 'calendar'])->name('agenda.index');

    Route::post('occasions/{occasion}/gallery/reorder', [AdminOccasionController::class, 'galleryReorder'])
        ->name('occasions.gallery.reorder');

Route::post('occasions/{occasion}/toggle-status', [
    App\Http\Controllers\admin\OccasionController::class,
    'toggleStatus'
])->name('occasions.toggleStatus');

});
