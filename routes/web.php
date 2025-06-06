<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Crypt;
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

Route::get('/', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
Route::delete('/users/delete/{encryptedId}', [UserController::class, 'destroy'])->name('users.destroy');
Route::put('/users/update/{encryptedId}', [UserController::class, 'update'])->name('users.update');
Route::get('/users/{encryptedId}', [UserController::class, 'show'])->name('users.show');
Route::get('/users/{encryptedId}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::get('/search', [UserController::class, 'search'])->name('users.search');
