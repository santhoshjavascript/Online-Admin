<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ApiCategoryController extends Controller
{
    public function __construct()
    {
        // No authentication middleware to allow public access
    }

    public function index()
    {
        try {
            $categories = Category::with('projects')->get();
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
            $category = Category::with('projects')->findOrFail($id);
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
            ]);

            Log::info('API Category Store method called', $request->all());

            $category = Category::create($validated);

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
            ]);

            Log::info('API Category Update method called', ['category_id' => $id, 'data' => $request->all()]);

            $category->update($validated);

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

            // Note: Projects linked to this category will have category_id set to NULL due to ON DELETE SET NULL
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