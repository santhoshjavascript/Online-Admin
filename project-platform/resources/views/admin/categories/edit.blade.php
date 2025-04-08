@extends('layouts.admin')

@section('content')
    <!-- Header Section -->
    <div class="head-title flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Category</h1>
        <nav class="breadcrumb flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.categories.index') }}" class="hover:text-blue-600">Categories</a>
            <span>/</span>
            <span>Edit</span>
        </nav>
    </div>

    <!-- Edit Form Section -->
    <div class="edit-container max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-2xl border border-gray-200">
        @if (session('success'))
            <div class="alert bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-200 flex items-center space-x-2">
                <i class='bx bx-check-circle text-green-500'></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name Field -->
            <div class="form-group">
                <label for="name" class="block text-lg font-semibold text-gray-800 mb-2">Category Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm hover:shadow-md @error('name') border-red-500 @enderror"
                       placeholder="Enter category name" />
                @error('name')
                    <p class="mt-2 text-sm text-red-600 flex items-center"><i class='bx bx-error mr-1'></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-md hover:shadow-lg">
                <i class='bx bx-save mr-2'></i>
                <span>Update Category</span>
            </button>
        </form>
    </div>

    <!-- Custom Styles -->
    <style>
        .head-title {
            background: var(--light);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            backdrop-filter: blur(5px);
        }

        .edit-container {
            background: var(--light);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(5px);
        }

        .form-group {
            margin-bottom: 1.75rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--dark);
            letter-spacing: 0.5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        input[type="text"]:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15), 0 4px 12px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .btn-submit {
            background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
            color: #ffffff;
            border-radius: 10px;
            padding: 0.875rem 1.5rem;
            transition: all 0.3s ease, transform 0.2s ease;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
        }

        .btn-submit:hover {
            background: linear-gradient(90deg, #16a34a 0%, #15803d 100%);
            box-shadow: 0 6px 16px rgba(34, 197, 94, 0.3);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .head-title {
                flex-direction: column;
                gap: 1.5rem;
            }

            .edit-container {
                padding: 16px;
            }

            .form-group label {
                font-size: 0.875rem;
            }

            input[type="text"] {
                padding: 0.625rem 0.875rem;
            }

            .btn-submit {
                width: 100%;
                justify-content: center;
            }
        }

        .text-red-600 {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
        }

        .alert {
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #22c55e;
            background: #f0fdf4;
        }
    </style>
@endsection