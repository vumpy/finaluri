<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/create', [App\Http\Controllers\CreateController::class, 'index']);

Route::get('/Error', [App\Http\Controllers\WelcomeController::class, 'error']);

// Route::get('/create/{quiz}', [App\Http\Controllers\CreateController::class, 'fill']);

Route::post('/create/{quiz}/{quest?}', [App\Http\Controllers\CreateController::class, 'fill']);

Route::get('/prequiz/{id}', [App\Http\Controllers\QuizController::class, 'index']);

Route::get('/quiz/{id}/{quest}', [App\Http\Controllers\QuizController::class, 'take']);

Route::get('/summary/{id}', [App\Http\Controllers\QuizController::class, 'summary'])->name('summary');

Route::get('/delete/{id}', [App\Http\Controllers\QuizController::class, 'delete']);

Route::get('/your_page', [App\Http\Controllers\HomeController::class, 'your_page']);

Route::get('/publish/{id}', [App\Http\Controllers\HomeController::class, 'publish']);