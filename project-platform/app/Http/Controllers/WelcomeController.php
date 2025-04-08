<?php

namespace App\Http\Controllers;

use App\Models\Project;

class WelcomeController extends Controller
{
    public function index()
    {
        // Fetch published projects
        $projects = Project::where('status', 'Published')->latest()->get();

        // Pass the $projects variable to the welcome view
        return view('welcome', compact('projects'));
    }
}