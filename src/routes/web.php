<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;

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

Route::middleware('auth')->group(function () {
    Route::get('/', [AuthController::class, 'index']);
});

// Route::get('/contact', [ContactController::class, 'contact'])->middleware('auth');
// Route::post('/contacts/confirm', [ContactController::class, 'confirm'])->middleware('auth');
// Route::post('/contacts', [ContactController::class, 'store'])->middleware('auth');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [ContactController::class, 'contact']);
    Route::post('/confirm', [ContactController::class, 'confirm']);
    Route::post('/thanks', [ContactController::class, 'store']);
    Route::get('/admin', [ContactController::class, 'admin']);
    Route::get('/search', [ContactController::class, 'search']);
});
