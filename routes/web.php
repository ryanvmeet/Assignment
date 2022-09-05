<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'getUser']);
    Route::get('/logout', [UserController::class, 'logoutUser']);
    Route::post('/upload-csv', [UserController::class, 'uploadCsv']);


});

Route::middleware(['guest'])->group(function() {
    Route::get('/login', function () {
        return view('authentication/login');
    })->name("login");

    Route::post('/login', [UserController::class, 'loginUser']);
    Route::post('/register', [UserController::class, 'createUser']);

    Route::get('/register', function () {
        return view('authentication/register');
    })->name("register");

    Route::get('/', function () {
        return view('welcome');
    });
});




