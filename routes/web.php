<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PublicOccasionController;
use App\Http\Controllers\admin\OccasionController as AdminOccasionController;
use App\Http\Controllers\admin\ReservationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SellCarController;
use App\Http\Controllers\WorkshopAppointmentController;
use App\Http\Controllers\admin\ReclameController;
use App\Http\Controllers\admin\WorkshopAppointmentController as AdminWorkshopAppointmentController;
use App\Http\Controllers\admin\SiteContentController;



// --- Publiek ---
Route::get('/', [PublicOccasionController::class, 'home'])->name('home');

// Aanbod-pagina (volwaardige page met filter + sort)
Route::get('/aanbod', [PublicOccasionController::class, 'aanbodPage'])->name('aanbod');

// Werkplaats-pagina (volwaardige page met smart booking, diensten en USPs)
Route::get('/werkplaats', [PublicOccasionController::class, 'werkplaatsPage'])->name('werkplaats');

// Diensten-pagina (leenauto + verhuurdiensten)
Route::get('/diensten', [PublicOccasionController::class, 'dienstenPage'])->name('diensten');

// Over ons-pagina (verhaal + team)
Route::get('/over', [PublicOccasionController::class, 'overPage'])->name('over');

// Contact-pagina (gegevens + map + formulier)
Route::get('/contact', [PublicOccasionController::class, 'contactPage'])->name('contact');

// /preview toont de nieuwe (dark) homepage als losse demo
Route::get('/preview', [PublicOccasionController::class, 'preview'])->name('preview');

// Admin auto-toevoegen demo
Route::get('/preview-admin', [PublicOccasionController::class, 'previewAdmin'])->name('preview.admin');

// Public APIs gebruikt door homepage + admin
Route::get('/api/rdw/{kenteken}', [PublicOccasionController::class, 'rdwPublic'])->name('rdw.public');
Route::get('/api/rdw-full/{kenteken}', [PublicOccasionController::class, 'rdwFull'])->name('rdw.full');
Route::post('/api/preview/ai-describe', [PublicOccasionController::class, 'aiDescribe'])->name('preview.ai');
Route::get('/api/preview/price-suggest', [PublicOccasionController::class, 'priceSuggest'])->name('preview.price');
Route::get('/occasions', [PublicOccasionController::class, 'index'])->name('occasions.index');
Route::get('/occasions/cards', [PublicOccasionController::class, 'cards'])->name('occasions.cards');
Route::get('/binnenkort', [PublicOccasionController::class, 'binnenkort'])->name('occasions.binnenkort');

Route::get('/occasions/{slug}', [PublicOccasionController::class, 'show'])->name('occasions.show');

Route::get('/werkplaats/afspraak', [WorkshopAppointmentController::class, 'step1'])->name('workshop.step1');
Route::post('/werkplaats/afspraak/stap-1', [WorkshopAppointmentController::class, 'postStep1'])->name('workshop.postStep1');

Route::get('/werkplaats/afspraak/werkzaamheden', [WorkshopAppointmentController::class, 'step2'])->name('workshop.step2');
Route::post('/werkplaats/afspraak/stap-2', [WorkshopAppointmentController::class, 'postStep2'])->name('workshop.postStep2');

Route::get('/werkplaats/afspraak/tijdstip', [WorkshopAppointmentController::class, 'step3'])->name('workshop.step3');
Route::post('/werkplaats/afspraak/stap-3', [WorkshopAppointmentController::class, 'postStep3'])->name('workshop.postStep3');

Route::get('/werkplaats/afspraak/contact', [WorkshopAppointmentController::class, 'step4'])->name('workshop.step4');
Route::post('/werkplaats/afspraak/afronden', [WorkshopAppointmentController::class, 'finish'])->name('workshop.finish');


// Contact
Route::post('contact', [ContactController::class, 'send'])->name('contact.send');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// --- Auth ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Auto verkopen (publieke pagina + submit)
Route::get('/auto-verkopen', [SellCarController::class, 'show'])->name('sellcar.show');
Route::post('/sell-car', [SellCarController::class, 'store'])->name('sellcar.store');

// Reserveren (publiek)
Route::get('/reserveren', [BookingController::class, 'show'])->name('booking.show');          // ?type=aanhanger|stofzuiger|koplampen
Route::get('/reserveren/slots', [BookingController::class, 'slots'])->name('booking.slots');  // AJAX: vrije tijden
Route::post('/reserveren', [BookingController::class, 'store'])->name('booking.store');

// --- Admin (achter login) ---
Route::middleware('auth')->prefix('admin')->as('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [\App\Http\Controllers\admin\DashboardController::class, 'index'])->name('dashboard');

    // Reserveringen overzicht (combineert aanhanger/stofzuiger/koplampen/werkplaats)
    Route::get('/reserveringen', [\App\Http\Controllers\admin\BookingsOverviewController::class, 'index'])->name('bookings.index');

    Route::get('reclame', [ReclameController::class, 'index'])->name('reclame.index');
    Route::get('reclame/nieuw', [ReclameController::class, 'create'])->name('reclame.create');
    Route::post('reclame', [ReclameController::class, 'store'])->name('reclame.store');

    Route::get('reclame/{reclame}/bewerken', [ReclameController::class, 'edit'])->name('reclame.edit');
    Route::put('reclame/{reclame}', [ReclameController::class, 'update'])->name('reclame.update');

    Route::get('reclame/{reclame}/pdf', [ReclameController::class, 'exportPdf'])->name('reclame.pdf');

    // Occasions CRUD
    Route::resource('occasions', AdminOccasionController::class)->except(['show']);
Route::get('occasions/rdw/{kenteken}', [AdminOccasionController::class, 'rdwLookup'])
    ->name('occasions.rdw');

    
    Route::get('/occasions/{occasion}/raamkaart', [\App\Http\Controllers\admin\OccasionPdfController::class, 'raamkaart'])
        ->name('occasions.raamkaart');

    // Notities per auto
    Route::post('occasions/{occasion}/notes',         [\App\Http\Controllers\admin\OccasionNoteController::class, 'store'])->name('occasions.notes.store');
    Route::delete('occasions/{occasion}/notes/{note}', [\App\Http\Controllers\admin\OccasionNoteController::class, 'destroy'])->name('occasions.notes.destroy');

    // Tasks (todo's, globaal en per auto)
    Route::get('tasks',                  [\App\Http\Controllers\admin\TaskController::class, 'index'])->name('tasks.index');
    Route::post('tasks',                 [\App\Http\Controllers\admin\TaskController::class, 'store'])->name('tasks.store');
    Route::put('tasks/{task}',           [\App\Http\Controllers\admin\TaskController::class, 'update'])->name('tasks.update');
    Route::post('tasks/{task}/toggle',   [\App\Http\Controllers\admin\TaskController::class, 'toggle'])->name('tasks.toggle');
    Route::delete('tasks/{task}',        [\App\Http\Controllers\admin\TaskController::class, 'destroy'])->name('tasks.destroy');

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

    // Werkplaats afspraken (vanaf het online formulier)
    Route::get('/werkplaats-afspraken',                 [AdminWorkshopAppointmentController::class, 'index'])->name('workshop.index');
    Route::get('/werkplaats-afspraken/{workshop}',      [AdminWorkshopAppointmentController::class, 'show'])->name('workshop.show');
    Route::post('/werkplaats-afspraken/{workshop}/status', [AdminWorkshopAppointmentController::class, 'updateStatus'])->name('workshop.status');
    Route::delete('/werkplaats-afspraken/{workshop}',   [AdminWorkshopAppointmentController::class, 'destroy'])->name('workshop.destroy');

    Route::post('occasions/{occasion}/gallery/reorder', [AdminOccasionController::class, 'galleryReorder'])
        ->name('occasions.gallery.reorder');

Route::post('occasions/{occasion}/toggle-status', [
    App\Http\Controllers\admin\OccasionController::class,
    'toggleStatus'
])->name('occasions.toggleStatus');

    // ===== Site-inhoud (CMS): teksten, kleuren, foto's per groep =====
    Route::get('/site-content/{group?}', [SiteContentController::class, 'edit'])->name('site-content.edit');
    Route::post('/site-content/{group}',  [SiteContentController::class, 'update'])->name('site-content.update');

});
