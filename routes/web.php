<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AbalController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/abals', [AbalController::class, 'abal'])->name('list.abal');
Route::get('/show-abals', [AbalController::class, 'show_abal'])->name('show.abal');
Route::POST('/add/abals', [AbalController::class, 'add_abal'])->name('add.abal');
Route::DELETE('/delete/{id}', [AbalController::class, 'delete'])->name('delete.abal');

Route::get('/show-publication', [AbalController::class, 'show_publication']);
Route::POST('/insert/publication', [AbalController::class, 'insert_publication']);