<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\Admin\RoomAllocationController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\GalleryController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\RoomServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Website
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/rooms', [RoomServiceController::class, 'index'])->name('rooms.index');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| Admin Authentication (guest-only)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.attempt');
    });

    Route::post('logout', [AuthController::class, 'logout'])
        ->middleware('auth:admin')
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Panel (protected)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth:admin', 'admin.active'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('rooms', RoomController::class)->except(['show']);

    Route::get('residents/check-identification', [ResidentController::class, 'checkIdentification'])
        ->name('residents.check-identification');
    Route::get('residents/export', [ResidentController::class, 'export'])->name('residents.export');
    Route::resource('residents', ResidentController::class);

    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::get('payments/resident-allocation', [PaymentController::class, 'residentAllocation'])
        ->name('payments.resident-allocation');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    Route::get('allocations', [RoomAllocationController::class, 'index'])->name('allocations.index');
    Route::get('allocations/check-active', [RoomAllocationController::class, 'checkActiveAllocation'])
        ->name('allocations.check-active');
    Route::get('allocations/create', [RoomAllocationController::class, 'create'])->name('allocations.create');
    Route::get('allocations/check-room-capacity', [RoomAllocationController::class, 'checkRoomCapacity'])
        ->name('allocations.check-room-capacity');
    Route::post('allocations', [RoomAllocationController::class, 'store'])->name('allocations.store');
    Route::patch('allocations/{allocation}/end', [RoomAllocationController::class, 'end'])->name('allocations.end');
    Route::delete('allocations/{allocation}', [RoomAllocationController::class, 'destroy'])->name('allocations.destroy');

    Route::resource('services', AdminServiceController::class)->except(['show']);

    Route::get('gallery', [AdminGalleryController::class, 'index'])->name('gallery.index');
    Route::get('gallery/create', [AdminGalleryController::class, 'create'])->name('gallery.create');
    Route::post('gallery', [AdminGalleryController::class, 'store'])->name('gallery.store');
    Route::delete('gallery/{galleryImage}', [AdminGalleryController::class, 'destroy'])->name('gallery.destroy');

    Route::get('contacts', [ContactMessageController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [ContactMessageController::class, 'show'])->name('contacts.show');
    Route::delete('contacts/{contact}', [ContactMessageController::class, 'destroy'])->name('contacts.destroy');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});
