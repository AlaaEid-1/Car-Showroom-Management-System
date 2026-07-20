<?php

use App\Http\Controllers\Dealer\CarController;
use App\Http\Controllers\Api\CarApiController;
use App\Http\Controllers\Dealer\DashboardController;
use App\Http\Controllers\Dealer\InquiryController;
use App\Http\Controllers\Dealer\ShowroomController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('api/vehicle-list', [CarApiController::class, 'index']);
Route::group([
    'as' => 'dashboarddealer.',
    'prefix' => 'dashboarddealer',
    'middleware' => ['auth:web', 'verified'],
], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');

    Route::get('/showroom', [ShowroomController::class, 'edit'])->name('showroom.edit');
    Route::post('/showroom', [ShowroomController::class, 'store'])->name('showroom.store');
    Route::patch('/showroom', [ShowroomController::class, 'update'])->name('showroom.update');

    Route::resource('cars', CarController::class);

    Route::patch('cars/{car}/restore', [CarController::class, 'restore'])
        ->name('cars.restore');
    Route::delete('cars/{car}/force-delete', [CarController::class, 'forceDelete'])
        ->name('cars.forceDelete');
    Route::delete('cars/{car}/images/{image}', [CarController::class, 'deleteImage'])
        ->name('cars.delete-image');
    Route::post('cars/{car}/images/{image}/main', [CarController::class, 'setMainImage'])
        ->name('cars.set-main-image');

    Route::get('/test-drives', [App\Http\Controllers\Dealer\TestDriveController::class, 'index'])
        ->name('test-drives.index');
    Route::patch('/test-drives/{testDrive}', [App\Http\Controllers\Dealer\TestDriveController::class, 'update'])
        ->name('test-drives.update');

    Route::post('/ai/generate', [App\Http\Controllers\Dealer\AI\CarAssistantController::class, 'generateDescription'])
        ->name('ai.generate');
    Route::post('/ai/improve/{car}', [App\Http\Controllers\Dealer\AI\CarAssistantController::class, 'improveListing'])
        ->name('ai.improve');
});

Route::get('/cars/search', [CarController::class, 'search'])
    ->name('cars.search');

Route::get('/cars/{car}', [CarController::class, 'show'])->name('cars.show');

Route::middleware('auth')->group(function () {

    Route::post('/cars/{car}/inquiry', [InquiryController::class, 'store'])
        ->name('inquiries.store');

    Route::get('/inquiries', [InquiryController::class, 'index'])
        ->name('inquiries.index');

    Route::get('/inquiries/{id}', [InquiryController::class, 'show'])
        ->name('inquiries.show');

    Route::post('/inquiries/{id}/message', [InquiryController::class, 'sendMessage'])
        ->name('inquiries.message');

    Route::post('/inquiries/{id}/close', [InquiryController::class, 'close'])
        ->name('inquiries.close');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/cars/{car}/favorite', [App\Http\Controllers\Customer\FavoriteController::class, 'store'])
        ->name('cars.favorite.store');
    Route::delete('/cars/{car}/favorite', [App\Http\Controllers\Customer\FavoriteController::class, 'destroy'])
        ->name('cars.favorite.destroy');
    Route::post('/cars/{car}/test-drive', [App\Http\Controllers\Customer\TestDriveController::class, 'store'])
        ->name('cars.test-drive.store');

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])
        ->name('notifications.read-all');

    Route::post('/become-dealer', [\App\Http\Controllers\DealerRequestController::class, 'store'])
        ->name('become-dealer');
});

// Admin Dashboard & Infrastructure Routes
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
        Route::patch('/users/{user}/status', [\App\Http\Controllers\AdminController::class, 'updateUserStatus'])->name('users.status');
        Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');
        Route::get('/dealers', [\App\Http\Controllers\AdminController::class, 'dealers'])->name('dealers');
        Route::get('/dealers/requests', [\App\Http\Controllers\AdminController::class, 'dealerRequests'])->name('dealers.requests');
        Route::patch('/dealers/{dealerRequest}/approve', [\App\Http\Controllers\AdminController::class, 'approveDealer'])->name('dealers.approve');
        Route::patch('/dealers/{dealerRequest}/reject', [\App\Http\Controllers\AdminController::class, 'rejectDealer'])->name('dealers.reject');
        Route::patch('/dealers/{user}/status', [\App\Http\Controllers\AdminController::class, 'updateDealerStatus'])->name('dealers.status');
        Route::get('/cars', [\App\Http\Controllers\AdminController::class, 'cars'])->name('cars');
        Route::delete('/cars/{car}', [\App\Http\Controllers\AdminController::class, 'deleteCar'])->name('cars.delete');
        Route::get('/showrooms', [\App\Http\Controllers\AdminController::class, 'showrooms'])->name('showrooms');
        Route::patch('/showrooms/{showroom}/status', [\App\Http\Controllers\AdminController::class, 'updateShowroomStatus'])->name('showrooms.status');
        Route::get('/inquiries', [\App\Http\Controllers\AdminController::class, 'inquiries'])->name('inquiries');
    });
