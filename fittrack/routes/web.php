<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
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
Route::get('/', [AuthController::class, 'index'])->name('login');

// -------------------------
// AUTH
// -------------------------
Route::prefix('auth')->group(function(){
    Route::get('/login', [AuthController::class, 'index'])->name('auth.index');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

    Route::get('/register', [AuthController::class, 'create'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'store'])->name('auth.register.store');

    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// -------------------------
// WELCOME
// -------------------------
Route::middleware('auth')->get('/welcome', [AuthController::class, 'welcome'])->name('welcome');

// -------------------------
// ACTIVITIES
// -------------------------
Route::middleware('auth')->prefix('activity')->group(function() {
    Route::get('/', [ActivityController::class, 'index'])->name('activity.index');
    Route::get('/create', [ActivityController::class, 'create'])->name('activity.create');
    Route::post('/store', [ActivityController::class, 'store'])->name('activity.store');
    Route::get('/edit/{id}', [ActivityController::class, 'edit'])->name('activity.edit');
    Route::put('/update/{id}', [ActivityController::class, 'update'])->name('activity.update');
    Route::delete('/destroy/{id}', [ActivityController::class, 'destroy'])->name('activity.destroy');
});