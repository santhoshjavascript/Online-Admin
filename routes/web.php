<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use App\Models\Project;
use App\Models\Category;
use App\Http\Controllers\Api\ApiProjectController;




Route::prefix('projects')->group(function () {
    Route::get('/', [ApiProjectController::class, 'index'])->name('api.projects.index');
    Route::get('/{id}', [ApiProjectController::class, 'show'])->name('api.projects.show');
    Route::post('/', [ApiProjectController::class, 'store'])->name('api.projects.store');
    Route::put('/{id}', [ApiProjectController::class, 'update'])->name('api.projects.update');
    Route::delete('/{id}', [ApiProjectController::class, 'destroy'])->name('api.projects.destroy');
}); // Added missing closing parenthesis

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
})->name('home');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $totalUsers = User::count();
        $totalProjects = Project::count();
        $totalCategories = Category::count();
        $recentProjects = Project::with('user')->latest()->take(5)->get();
        return view('admin.dashboard', compact('totalUsers', 'totalProjects', 'totalCategories', 'recentProjects'));
    })->name('dashboard');

    Route::resource('projects', ProjectController::class)->names([
        'index' => 'projects.index',
        'create' => 'projects.create',
        'store' => 'projects.store',
        'edit' => 'projects.edit',
        'update' => 'projects.update',
        'destroy' => 'projects.destroy',
    ]);

    Route::resource('categories', CategoryController::class)->names([
        'index' => 'categories.index',
        'create' => 'categories.create',
        'store' => 'categories.store',
        'edit' => 'categories.edit',
        'update' => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);
});