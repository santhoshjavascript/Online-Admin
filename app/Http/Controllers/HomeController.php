<?php

namespace App\Http\Controllers;

use App\Models\Project;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Remove 'auth' middleware if you want the home page to be accessible to all users
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Fetch published projects
        $projects = Project::where('status', 'Published')->latest()->get();

        // Pass the $projects variable to the welcome view
        return view('welcome', compact('projects'));
    }
}