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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/tasks/fetch', [App\Http\Controllers\TaskController::class, 'fetch'])->name('tasks.fetch');
Route::post('/tasks/update/order', [App\Http\Controllers\TaskController::class, 'updateOrder'])->name('tasks.update.order');
Route::resource('tasks', 'App\Http\Controllers\TaskController');

