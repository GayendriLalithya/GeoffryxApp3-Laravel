<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/index', function () {
    return view('index');
});

Route::get('/register', function () {
    return view('auth/register');
});

use App\Http\Controllers\Auth\RegisterController;

// Display the registration form
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Handle the registration submission
Route::post('register', [RegisterController::class, 'register'])->name('register.submit');
