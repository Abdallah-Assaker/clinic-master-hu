<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\HealthController;
use Modules\Doctors\Http\Controllers\DoctorsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationsController;

// Health check endpoints (for Docker/monitoring)
Route::get('/health', [HealthController::class, 'check'])->name('health.check');
Route::get('/health/detailed', [HealthController::class, 'detailed'])->name('health.detailed');

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/search', [PageController::class, 'search'])->name('search');

Route::get('/governorates/{governorate}/cities', [PageController::class, 'getCities'])->name('governorates.cities');
Route::get('/cities', [PageController::class, 'getAllCities'])->name('cities.all');
Route::get('/doctors/filter', [DoctorsController::class, 'filter'])->name('doctors.filter');

Route::middleware(['auth:web'])->group(function () {
    Route::get('/admin/notifications', [NotificationsController::class, 'index']);
    Route::get('/admin/notifications/count', [NotificationsController::class, 'count']);
    Route::post('/admin/notifications/{id}/mark-as-read', [NotificationsController::class, 'markAsRead']);
    Route::post('/admin/notifications/mark-all-read', [NotificationsController::class, 'markAllAsRead']);
});
