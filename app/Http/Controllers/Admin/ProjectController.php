<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $projects = Project::with('category')->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.projects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Store method called', $request->all());

            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:projects,title',
                'description' => 'required|string',
                'video_url' => 'required|string|starts_with:https://www.youtube.com/watch?v=|max:255',
                'abstract_url' => 'nullable|file|mimetypes:application/pdf|max:10240',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category_id' => 'nullable|exists:categories,id',
                'new_category' => 'nullable|string|max:255|unique:categories,name', // Remove required_if
            ]);

            Log::info('Validated data: ', $validated);

            // Handle new category creation only if new_category is provided and category_id is not
            if (empty($request->category_id) && !empty($request->new_category)) {
                $category = Category::create([
                    'name' => $request->new_category,
                    'description' => 'Auto-generated via project creation',
                ]);
                $validated['category_id'] = $category->id;
            } elseif (empty($request->category_id) && empty($request->new_category)) {
                throw new \Exception('Please select an existing category or provide a new category name.');
            }

            // Handle file uploads
            if ($request->hasFile('abstract_url')) {
                $validated['abstract_url'] = $request->file('abstract_url')->store('abstracts', 'public');
            }
            if ($request->hasFile('thumbnail')) {
                $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            $validated['uploaded_by'] = auth()->id();
            $validated['status'] = 'Draft';

            $project = Project::create($validated);

            return redirect()->route('admin.projects.edit', $project)
                             ->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            Log::error('Project creation failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit(Project $project)
    {
        $categories = Category::all();
        return view('admin.projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        try {
            Log::info('Update method called', ['project_id' => $project->id, 'data' => $request->all()]);

            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:projects,title,' . $project->id,
                'description' => 'required|string',
                'video_url' => 'required|string|starts_with:https://www.youtube.com/watch?v=|max:255',
                'abstract_url' => 'nullable|file|mimetypes:application/pdf|max:10240',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category_id' => 'nullable|exists:categories,id',
                'new_category' => 'nullable|string|max:255|unique:categories,name,' . ($project->category_id ? $project->category->id : ''),
            ]);

            Log::info('Validated data: ', $validated);

            // Handle new category creation only if new_category is provided and category_id is not
            if (empty($request->category_id) && !empty($request->new_category)) {
                $category = Category::create([
                    'name' => $request->new_category,
                    'description' => 'Auto-generated via project update',
                ]);
                $validated['category_id'] = $category->id;
            } elseif (empty($request->category_id) && empty($request->new_category)) {
                throw new \Exception('Please select an existing category or provide a new category name.');
            }

            // Handle file uploads
            if ($request->hasFile('abstract_url')) {
                if ($project->abstract_url) {
                    Storage::disk('public')->delete($project->abstract_url);
                }
                $validated['abstract_url'] = $request->file('abstract_url')->store('abstracts', 'public');
            } else {
                $validated['abstract_url'] = $project->abstract_url;
            }

            if ($request->hasFile('thumbnail')) {
                if ($project->thumbnail) {
                    Storage::disk('public')->delete($project->thumbnail);
                }
                $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            } else {
                $validated['thumbnail'] = $project->thumbnail;
            }

            $project->update($validated);

            return redirect()->route('admin.projects.edit', $project)
                             ->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            Log::error('Project update failed: ' . $e->getMessage());
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(Project $project)
    {
        try {
            if ($project->abstract_url) {
                Storage::disk('public')->delete($project->abstract_url);
            }
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $project->delete();

            return redirect()->route('admin.projects.index')
                             ->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Project deletion failed: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while deleting the project.');
        }
    }
}