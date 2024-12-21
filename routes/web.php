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

// web.php
use App\Http\Controllers\frontend\ProfessionalListController;

Route::get('/professionals', [ProfessionalListController::class, 'index']);
Route::get('/professionals/{id}', [ProfessionalListController::class, 'showProfessional']);

use App\Http\Controllers\User\WorkController;

Route::post('/work/store', [WorkController::class, 'store'])->name('work.store');
Route::post('/work/{workId}/confirm-completion', [WorkController::class, 'confirmCompletion'])->name('work.confirmCompletion');


use App\Http\Controllers\Professional\ProjectRequestController;

Route::get('/professional/requests', [ProjectRequestController::class, 'index'])
    ->name('professional.requests');

Route::post('/accept-work', [ProjectRequestController::class, 'acceptWork'])->name('accept-work');
Route::post('/reject-work', [ProjectRequestController::class, 'rejectWork'])->name('reject-work');

use App\Http\Controllers\User\ProjectController;

Route::get('/manage-projects', [ProjectController::class, 'manageProjects'])->name('manage.projects');


use App\Http\Controllers\Professional\TeamController;

Route::get('/team-members/{workId}', [TeamController::class, 'loadTeamMembers']);
Route::post('/team-members/update-status', [TeamController::class, 'updateStatus'])->name('team-members.update-status');

use App\Http\Controllers\Professional\ProfessionalRatingController;

Route::get('/work/{work_id}/rate-professionals', [ProfessionalRatingController::class, 'showRatingPage'])->name('professional.rating');

use App\Http\Controllers\User\RatingController;

Route::post('/ratings/submit', [RatingController::class, 'submitRatings'])->name('professional.submitRatings');
Route::post('/ratings/submit', [WorkController::class, 'submitRatings'])->name('professional.submitRatings');


Route::get('/search-professionals', [ProfessionalController::class, 'searchAjax'])->name('professionals.search.ajax');


// Group Chat

use App\Http\Controllers\GroupChatController;

Route::get('/group-chat/{id}/{email}', [GroupChatController::class, 'showGroupChatView'])->name('group-chat.view');

// Payment

use App\Http\Controllers\PaymentController;

Route::post('/payment/return', [PaymentController::class, 'paymentReturn'])->name('payment.return');
Route::post('/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');
Route::post('/payment/notify', [PaymentController::class, 'paymentNotify'])->name('payment.notify');

Route::post('/payment/execute', [PaymentController::class, 'executePayment'])->name('payment.execute');

Route::get('/payment/initiate/{work_id}', [PaymentController::class, 'initiatePayment'])->name('payment.initiate');

// Admin Manage user controller

use App\Http\Controllers\Admin\ManageUserController;

Route::get('/users', [ManageUserController::class, 'index'])->name('user.details');
Route::get('/users/edit/{id}', [ManageUserController::class, 'editUser'])->name('user.edit');
Route::delete('/users/delete/{id}', [ManageUserController::class, 'deleteUser'])->name('user.delete');
