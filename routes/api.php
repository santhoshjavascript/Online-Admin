<?php

use App\Http\Controllers\Api\ApiProjectController;
use Illuminate\Support\Facades\Route;

Route::prefix('projects')->group(function () {
    Route::get('/', [ApiProjectController::class, 'index'])->name('api.projects.index');
    Route::get('/{id}', [ApiProjectController::class, 'show'])->name('api.projects.show');
    Route::post('/', [ApiProjectController::class, 'store'])->name('api.projects.store');
    Route::put('/{id}', [ApiProjectController::class, 'update'])->name('api.projects.update');
    Route::delete('/{id}', [ApiProjectController::class, 'destroy'])->name('api.projects.destroy');
}); // Added missing closing parenthesis