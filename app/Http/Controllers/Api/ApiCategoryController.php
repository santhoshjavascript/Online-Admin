<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApiCategoryController extends Controller
{
    public function __construct()
    {
        // No authentication middleware to allow public access
    }

    public function index()
    {
        try {
            $categories = Category::with(['projects' => function ($query) {
                $query->select('id', 'title', 'description', 'video_url', 'abstract_url', 'thumbnail', 'status', 'category_id', 'uploaded_by');
            }])->get()->map(function ($category) {
                // Append full URLs for projects' thumbnail and abstract_url
                $category->projects->each(function ($project) {
                    $project->thumbnail = $project->thumbnail ? url('storage/' . $project->thumbnail) : null;
                    $project->abstract_url = $project->abstract_url ? url('storage/' . $project->abstract_url) : null;
                });
                // Append full URL for category image
                $category->image = $category->image ? url('storage/' . $category->image) : null;
                return $category;
            });

            return response()->json([
                'status' => 'success',
                'data' => $categories,
                'message' => 'Categories retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('API Error: Failed to retrieve categories - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve categories',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::with(['projects' => function ($query) {
                $query->select('id', 'title', 'description', 'video_url', 'abstract_url', 'thumbnail', 'status', 'category_id', 'uploaded_by');
            }])->findOrFail($id);

            // Append full URLs for projects' thumbnail and abstract_url
            $category->projects->each(function ($project) {
                $project->thumbnail = $project->thumbnail ? url('storage/' . $project->thumbnail) : null;
                $project->abstract_url = $project->abstract_url ? url('storage/' . $project->abstract_url) : null;
            });
            // Append full URL for category image
            $category->image = $category->image ? url('storage/' . $category->image) : null;

            return response()->json([
                'status' => 'success',
                'data' => $category,
                'message' => 'Category retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('API Error: Failed to retrieve category ' . $id . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Max 2MB
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('categories', 'public');
            }

            Log::info('API Category Store method called', $request->all());

            $category = Category::create($validated);

            // Append full URL for category image
            $category->image = $category->image ? url('storage/' . $category->image) : null;

            return response()->json([
                'status' => 'success',
                'data' => $category,
                'message' => 'Category created successfully',
            ], 201);
        } catch (\Exception $e) {
            Log::error('API Error: Category creation failed - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Category creation failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $validated['image'] = $request->file('image')->store('categories', 'public');
            }

            Log::info('API Category Update method called', ['category_id' => $id, 'data' => $request->all()]);

            $category->update($validated);

            // Append full URL for category image
            $category->image = $category->image ? url('storage/' . $category->image) : null;

            return response()->json([
                'status' => 'success',
                'data' => $category,
                'message' => 'Category updated successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('API Error: Category update failed for ID ' . $id . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Category update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Prevent deletion if category has associated projects
            if ($category->projects()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete category with associated projects',
                ], 400);
            }

            // Delete image if it exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $category->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('API Error: Category deletion failed for ID ' . $id . ' - ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Category deletion failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}