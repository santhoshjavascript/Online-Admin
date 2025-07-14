@extends('layouts.admin')

@section('content')
    <!-- Header Section -->
    <div class="head-title flex items-center justify-between mb-6" style="margin-bottom: 1rem">
        <h1 class="text-2xl font-bold text-gray-800">Manage Categories</h1>
        <nav class="breadcrumb flex items-center space-x-2 text-sm text-gray-600">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <span>Categories</span>
        </nav>
    </div>

    <!-- Create New Category Button -->
    <div class="mb-6" style="margin-bottom: 1rem">
        <a href="{{ route('admin.categories.create') }}"
           class="btn-create inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all duration-300 shadow-sm hover:shadow-md">
            <i class='bx bx-plus mr-2'></i>
            <span>Create New Category</span>
        </a>
    </div>

    <!-- Alert Message -->
    @if ($categories->isEmpty())
        <div class="alert bg-yellow-50 text-yellow-700 p-4 rounded-xl mb-6 border border-yellow-200 flex items-center space-x-2">
            <i class='bx bx-info-circle text-yellow-500'></i>
            <span>No categories found. Please create a new category.</span>
        </div>
    @endif

    <!-- Categories Table -->
    <div class="table-data">
        <div class="order bg-white p-6 rounded-xl shadow-lg overflow-hidden">
            <div class="head flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold text-gray-900">Categories List</h3>
            </div>
            <div class="table-responsive">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-4 text-left text-sm font-medium text-gray-700 border-b border-gray-200">Number</th>
                            <th class="p-4 text-left text-sm font-medium text-gray-700 border-b border-gray-200">Image</th>
                            <th class="p-4 text-left text-sm font-medium text-gray-700 border-b border-gray-200">Name</th>
                            <th class="p-4 text-left text-sm font-medium text-gray-700 border-b border-gray-200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1; // Initialize counter
                        @endphp
                        @forelse ($categories as $category)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="p-4 text-gray-800 border-b border-gray-200">{{ $counter++ }}</td> <!-- Use counter and increment -->
                                <td class="p-4 text-gray-800 border-b border-gray-200">
                                    @if ($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="index-img w-50 h-50 object-cover rounded-lg shadow-sm"> <!-- Set to 50x50 pixels -->
                                    @else
                                        <span class="text-gray-500">No Image</span>
                                    @endif
                                </td>
                                <td class="p-4 text-gray-800 border-b border-gray-200">{{ $category->name }}</td>
                                <td class="p-4 border-b border-gray-200">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn-edit inline-flex items-center px-3 py-1 bg-yellow-400 text-white rounded-full hover:bg-yellow-500 transition duration-200">
                                        <i class='bx bx-edit-alt text-sm'></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete inline-flex items-center px-3 py-1 bg-red-500 text-white rounded-full hover:bg-red-600 transition duration-200 ml-2">
                                            <i class='bx bx-trash text-sm'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500 border-b border-gray-200">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        .index-img {
            width: 50px;
            height: 50px;
        }

        .head-title {
            background: var(--light);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(5px);
        }

        .breadcrumb li a {
            transition: color 0.3s ease;
        }

        .edit-container {
            background: var(--light);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(5px);
        }

        .btn-create {
            background: linear-gradient(90deg, #22c55e 0%, #16a34a 100%);
            color: #ffffff;
            border-radius: 10px;
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease, transform 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-weight: 600;
            box-shadow: 0 3px 9px rgba(34, 197, 94, 0.2);
        }

        .btn-create:hover {
            background: linear-gradient(90deg, #16a34a 0%, #15803d 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.25rem;
            border-left: 3px solid #facc15;
            background: #fef9c3;
        }

        .alert span {
            font-size: 0.9rem;
            color: #92400e;
        }

        @media (max-width: 768px) {
            .head-title {
                flex-direction: column;
                gap: 1rem;
            }

            .edit-container {
                padding: 16px;
            }

            th, td {
                font-size: 0.8rem;
                padding: 0.75rem;
            }

            .btn-create, .btn-edit, .btn-delete {
                width: 100%;
                justify-content: center;
            }
        }

        .table-data {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-top: 24px;
            width: 100%;
            color: var(--dark);
        }

        .table-data > div {
            border-radius: 20px;
            background: var(--light);
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        .head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-auto th,
        .table-auto td {
            padding: 12px;
            border-bottom: 1px solid var(--grey);
        }

        .table-auto th {
            background-color: var(--light);
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-grey);
            text-transform: uppercase;
        }

        .table-auto td {
            font-size: 14px;
            color: var(--dark);
        }

        .btn-edit,
        .btn-delete {
            border: none;
            border-radius: 20px;
            padding: 5px 10px;
            font-size: 12px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
    </style>
@endsection