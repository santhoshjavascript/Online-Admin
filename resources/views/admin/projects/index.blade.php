@extends('layouts.admin')

@section('content')
    <!-- Header Section -->
    <div class="head-title flex justify-between items-center mb-8 bg-white p-6 rounded-xl shadow-lg">

        <a href="{{ route('admin.projects.create') }}" class="btn-download inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-md hover:shadow-lg">
            <i class='bx bxs-plus-circle mr-2'></i>
            <span class="text">Add New Project</span>
        </a>
    </div>

    <!-- Projects List Section -->
    <div class="table-data">
        <div class="order bg-white p-6 rounded-xl shadow-lg overflow-hidden">
            <div class="head flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold text-gray-900">Projects List</h3>

            </div>
            <div class="table-responsive">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-4 text-left text-sm font-medium text-gray-700 border-b border-gray-200">ID</th>
                            <th class="p-4 text-left text-sm font-medium text-gray-700 border-b border-gray-200">Title</th>
                            <th class="p-4 text-left text-sm font-medium text-gray-700 border-b border-gray-200">Category</th>
                            <th class="p-4 text-left text-sm font-medium text-gray-700 border-b border-gray-200">Status</th>
                            <th class="p-4 text-left text-sm font-medium text-gray-700 border-b border-gray-200">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($projects as $project)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="p-4 text-gray-800 border-b border-gray-200">{{ $project->id }}</td>
                                <td class="p-4 text-gray-800 border-b border-gray-200">{{ $project->title }}</td>
                                <td class="p-4 text-gray-600 border-b border-gray-200">{{ $project->category->name ?? 'N/A' }}</td>
                                <td class="p-4 border-b border-gray-200">
                                    <span class="status inline-block px-3 py-1 text-sm font-semibold rounded-full">
                                          {{-- style="{{ (strtolower($project->status) === 'published') ? 'background-color: #dbeafe; color: #1e40af' : (strtolower($project->status) === 'draft') ? 'background-color: #fefcbf; color: #92400e' : 'background-color: #e5e7eb; color: #4b5563' }}"> --}}
                                        {{ $project->status }}
                                    </span>
                                </td>
                                <td class="p-4 border-b border-gray-200">
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="btn-edit inline-flex items-center px-3 py-1 bg-yellow-400 text-white rounded-full hover:bg-yellow-500 transition duration-200">
                                        <i class='bx bx-edit-alt text-sm'></i>
                                    </a>
                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
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
                                <td colspan="5" class="p-4 text-center text-gray-500 border-b border-gray-200">No projects found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .head-title {
            background: var(--light);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .breadcrumb li a {
            transition: color 0.3s ease;
        }

        .btn-download {
            background: var(--blue);
            color: var(--light);
            border-radius: 20px;
            padding: 10px 20px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-download:hover {
            background: var(--light-blue);
            color: var(--dark);
            box-shadow: 0 4px 12px rgba(60, 145, 230, 0.3);
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
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        .head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .head h3 {
            margin-right: auto;
            font-size: 24px;
            font-weight: 600;
            color: var(--dark);
        }

        .head .bx {
            cursor: pointer;
            font-size: 20px;
            transition: color 0.3s ease;
        }

        .head .bx:hover {
            color: var(--blue);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table-auto {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-auto th,
        .table-auto td {
            padding: 16px;
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
            font-size: 15px;
            color: var(--dark);
        }

        .table-auto tbody tr:hover {
            background-color: var(--grey);
            transition: background-color 0.3s ease;
        }

        .table-auto td .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        .table-auto td .status.draft {
            background-color: var(--orange);
            color: var(--light);
        }

        .table-auto td .status.published {
            background-color: var(--blue);
            color: var(--light);
        }

        .table-auto td .status.archived {
            background-color: var(--dark-grey);
            color: var(--light);
        }

        .btn-edit,
        .btn-delete {
            border: none;
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-edit:hover {
            background-color: #facc15;
            color: var(--light);
            transform: translateY(-2px);
        }

        .btn-delete:hover {
            background-color: #dc2626;
            color: var(--light);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .head-title {
                flex-direction: column;
                gap: 1rem;
            }

            .btn-download {
                width: 100%;
                justify-content: center;
            }

            .head {
                flex-direction: column;
                gap: 1rem;
            }

            .table-auto th,
            .table-auto td {
                padding: 12px;
                font-size: 13px;
            }
        }
    </style>
@endsection