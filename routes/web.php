<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUsedController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Маршруты аутентификации (для гостей)
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUsedController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUsedController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Маршруты корзины (только для авторизованных пользователей)
Route::middleware(['auth'])->group(function () {
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{serviceId}', [CartController::class, 'add'])->name('add');
        Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
        Route::patch('/{id}/update-employee-time', [CartController::class, 'updateEmployeeAndTime'])->name('update.employee.time');
        Route::get('/busy-slots', [CartController::class, 'getBusySlots'])->name('busy-slots');
        Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    });

    // Профиль пользователя
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    // Выход из системы
    Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
