<?php

use App\Http\Controllers\Api\ApiProjectController;
use App\Http\Controllers\Api\ApiCategoryController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('projects')->group(function () {
    Route::get('/', [ApiProjectController::class, 'index'])->name('api.projects.index');
    Route::get('/{id}', [ApiProjectController::class, 'show'])->name('api.projects.show');
    Route::post('/', [ApiProjectController::class, 'store'])->name('api.projects.store');
    Route::put('/{id}', [ApiProjectController::class, 'update'])->name('api.projects.update');
    Route::delete('/{id}', [ApiProjectController::class, 'destroy'])->name('api.projects.destroy');
});

Route::prefix('categories')->group(function () {
    Route::get('/', [ApiCategoryController::class, 'index'])->name('api.categories.index');
    Route::get('/{id}', [ApiCategoryController::class, 'show'])->name('api.categories.show');
    Route::post('/', [ApiCategoryController::class, 'store'])->name('api.categories.store');
    Route::put('/{id}', [ApiCategoryController::class, 'update'])->name('api.categories.update');
    Route::delete('/{id}', [ApiCategoryController::class, 'destroy'])->name('api.categories.destroy');
});

Route::post('login', [AuthController::class, 'login'])->name('api.login');