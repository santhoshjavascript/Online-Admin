<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ApiProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); // Requires API authentication (e.g., Sanctum token)
    }

    public function index()
    {
        try {
            $projects = Project::with('category', 'user')->get();
            return response()->json([
                'status' => 'success',
                'data' => $projects,
                'message' => 'Projects retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('API Error: Failed to retrieve projects - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve projects',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $project = Project::with('category', 'user')->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $project,
                'message' => 'Project retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('API Error: Failed to retrieve project ' . $id . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:projects,title',
                'description' => 'required|string',
                'video_url' => 'required|string|starts_with:https://www.youtube.com/watch?v=|max:255',
                'abstract_url' => 'nullable|file|mimetypes:application/pdf|max:10240',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category_id' => 'nullable|exists:categories,id',
                'new_category' => 'nullable|string|max:255|unique:categories,name',
            ]);

            Log::info('API Store method called', $request->all());

            if (empty($request->category_id) && !empty($request->new_category)) {
                $category = Category::create([
                    'name' => $request->new_category,
                    'description' => 'Auto-generated via API project creation',
                ]);
                $validated['category_id'] = $category->id;
            } elseif (empty($request->category_id) && empty($request->new_category)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please select an existing category or provide a new category name.',
                ], 400);
            }

            if ($request->hasFile('abstract_url')) {
                $validated['abstract_url'] = $request->file('abstract_url')->store('abstracts', 'public');
            }
            if ($request->hasFile('thumbnail')) {
                $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
            }

            $validated['uploaded_by'] = auth()->guard('api')->user()->id ?? null;
            $validated['status'] = 'Draft';

            $project = Project::create($validated);

            return response()->json([
                'status' => 'success',
                'data' => $project,
                'message' => 'Project created successfully',
            ], 201);
        } catch (\Exception $e) {
            Log::error('API Error: Project creation failed - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Project creation failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $project = Project::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:projects,title,' . $project->id,
                'description' => 'required|string',
                'video_url' => 'required|string|starts_with:https://www.youtube.com/watch?v=|max:255',
                'abstract_url' => 'nullable|file|mimetypes:application/pdf|max:10240',
                'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category_id' => 'nullable|exists:categories,id',
                'new_category' => 'nullable|string|max:255|unique:categories,name,' . ($project->category_id ? $project->category->id : ''),
            ]);

            Log::info('API Update method called', ['project_id' => $id, 'data' => $request->all()]);

            if (empty($request->category_id) && !empty($request->new_category)) {
                $category = Category::create([
                    'name' => $request->new_category,
                    'description' => 'Auto-generated via API project update',
                ]);
                $validated['category_id'] = $category->id;
            } elseif (empty($request->category_id) && empty($request->new_category)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please select an existing category or provide a new category name.',
                ], 400);
            }

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

            return response()->json([
                'status' => 'success',
                'data' => $project,
                'message' => 'Project updated successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('API Error: Project update failed for ID ' . $id . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Project update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);

            if ($project->abstract_url) {
                Storage::disk('public')->delete($project->abstract_url);
            }
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $project->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Project deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('API Error: Project deletion failed for ID ' . $id . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Project deletion failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}