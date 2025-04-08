<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalProjects = Project::count();
        $totalCategories = Category::count();
        $recentProjects = Project::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalUsers', 'totalProjects', 'totalCategories', 'recentProjects'));
    }
}