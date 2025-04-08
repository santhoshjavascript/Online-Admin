@extends('layouts.admin')

@section('content')
    <div class="head-title flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Create Project</h1>
        <nav class="breadcrumb flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.projects.index') }}" class="hover:text-blue-600">Projects</a>
            <span>/</span>
            <span>Create</span>
        </nav>
    </div>

    <div class="edit-container max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-2xl border border-gray-200">
        @if (session('success'))
            <div class="alert bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-200 flex items-center space-x-2">
                <i class='bx bx-check-circle text-green-500'></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-200 flex items-center space-x-2">
                <i class='bx bx-error text-red-500'></i>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-200 flex items-center space-x-2">
                <i class='bx bx-error text-red-500'></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="form-group">
                <label for="title" class="block text-lg font-semibold text-gray-800 mb-2">Project Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md @error('title') border-red-500 @enderror"
                       placeholder="Enter project title" required />
                @error('title')
                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class='bx bx-error mr-1'></i>{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="block text-lg font-semibold text-gray-800 mb-2">Description <span class="text-red-500">*</span></label>
                <textarea name="description" id="description"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md @error('description') border-red-500 @enderror"
                          rows="5" placeholder="Enter project description" required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class='bx bx-error mr-1'></i>{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="category_id" class="block text-lg font-semibold text-gray-800 mb-2">Category <span class="text-red-500">*</span></label>
                <select name="category_id" id="category_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md @error('category_id') border-red-500 @enderror">
                    <option value="">Create New Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class='bx bx-error mr-1'></i>{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group" id="new-category-group" style="display: {{ old('category_id') ? 'none' : 'block' }};">
                <label for="new_category" class="block text-lg font-semibold text-gray-800 mb-2">New Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="new_category" id="new_category" value="{{ old('new_category') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md @error('new_category') border-red-500 @enderror"
                       placeholder="Enter new category name" />
                @error('new_category')
                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class='bx bx-error mr-1'></i>{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="video_url" class="block text-lg font-semibold text-gray-800 mb-2">YouTube URL <span class="text-red-500">*</span></label>
                <input type="text" name="video_url" id="video_url" value="{{ old('video_url') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md @error('video_url') border-red-500 @enderror"
                       placeholder="e.g., https://www.youtube.com/watch?v=abc123" required />
                @error('video_url')
                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class='bx bx-error mr-1'></i>{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="abstract_url" class="block text-lg font-semibold text-gray-800 mb-2">Abstract PDF (optional)</label>
                <input type="file" name="abstract_url" id="abstract_url"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md @error('abstract_url') border-red-500 @enderror" />
                @error('abstract_url')
                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class='bx bx-error mr-1'></i>{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="thumbnail" class="block text-lg font-semibold text-gray-800 mb-2">Thumbnail (optional)</label>
                <input type="file" name="thumbnail" id="thumbnail"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md @error('thumbnail') border-red-500 @enderror" />
                @error('thumbnail')
                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class='bx bx-error mr-1'></i>{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-submit inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-md hover:shadow-lg">
                <i class='bx bx-save mr-2'></i>
                <span>Create Project</span>
            </button>
        </form>
    </div>

    <style>
        .head-title { background: var(--light); border-radius: 20px; padding: 24px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05); margin-bottom: 2rem; backdrop-filter: blur(5px); }
        .edit-container { background: var(--light); border-radius: 20px; padding: 24px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05); backdrop-filter: blur(5px); }
        .form-group { margin-bottom: 1.75rem; }
        .form-group label { display: block; margin-bottom: 0.75rem; font-weight: 600; color: var(--dark); letter-spacing: 0.5px; }
        input[type="text"], textarea, select, input[type="file"] { width: 100%; padding: 0.875rem 1rem; border: 2px solid #e5e7eb; border-radius: 10px; transition: border-color 0.3s ease, box-shadow 0.3s ease; background: #ffffff; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); }
        input[type="text"]:focus, textarea:focus, select:focus, input[type="file"]:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15), 0 4px 12px rgba(59, 130, 246, 0.1); outline: none; }
        .border-red-500 { border-color: #ef4444; }
        .text-red-600 { color: #ef4444; font-size: 0.875rem; margin-top: 0.5rem; display: flex; align-items: center; }
        .alert { border-radius: 10px; padding: 1.25rem; margin-bottom: 1.5rem; border-left: 4px solid #22c55e; background: #f0fdf4; }
        .alert.bg-red-50 { border-left-color: #ef4444; background: #fef2f2; }
        .btn-submit { background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%); color: #ffffff; border-radius: 10px; padding: 0.875rem 1.5rem; transition: all 0.3s ease, transform 0.2s ease; font-weight: 600; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2); }
        .btn-submit:hover { background: linear-gradient(90deg, #16a34a 0%, #15803d 100%); box-shadow: 0 6px 16px rgba(34, 197, 94, 0.3); transform: translateY(-2px); }
        @media (max-width: 768px) { .head-title { flex-direction: column; gap: 1.5rem; } .edit-container { padding: 16px; } .form-group label { font-size: 0.875rem; } input[type="text"], textarea, select, input[type="file"] { padding: 0.625rem 0.875rem; } .btn-submit { width: 100%; justify-content: center; } }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            const newCategoryGroup = document.getElementById('new-category-group');
            if (categorySelect && newCategoryGroup) {
                // Initial state based on current value
                newCategoryGroup.style.display = categorySelect.value === '' ? 'block' : 'none';

                // Add change event listener
                categorySelect.addEventListener('change', function() {
                    newCategoryGroup.style.display = this.value === '' ? 'block' : 'none';
                });
            }
        });
    </script>
@endsection