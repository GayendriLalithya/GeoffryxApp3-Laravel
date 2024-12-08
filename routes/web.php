<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\ProfilePictureController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\NotificationController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('layouts.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Dashboard Routes
Route::get('/user/dashboard', [DashboardController::class, 'showDashboard'])->name('user.dashboard');

Route::post('/profile-picture/upload', [ProfilePictureController::class, 'store'])
    ->name('profile-picture.store')
    ->middleware('auth');

// User - Professional Acccount Request
Route::post('/request-verification', [ProfessionalController::class, 'requestVerification'])->name('requestVerification');


use App\Http\Controllers\Admin\VerifyController;

Route::get('/admin/requests', [VerifyController::class, 'showRequests'])->name('admin.requests');

use App\Http\Controllers\Admin\RequestController;

// Accept verification request
Route::get('/requests/accept/{verify_id}', [RequestController::class, 'acceptVerification'])->name('requests.accept');

// Reject verification request
Route::post('/requests/reject/{verify_id}', [RequestController::class, 'rejectVerification'])->name('requests.reject');


Route::patch('/notifications/mark-read/{id}', [NotificationController::class, 'markAsRead']);
Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.getUnreadCount');
