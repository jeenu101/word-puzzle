@extends('layouts.app')

@section('title', 'Admin Dashboard - Word Puzzles')

@section('content')

    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Puzzle Management</h1>
            <p class="text-gray-600">Create and manage word puzzles</p>
        </div>
        <a 
            href="{{ route('admin.puzzles.create') }}" 
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200"
        >
            <i class="fas fa-plus mr-2"></i>
            Create Puzzle
        </a>
    </div>


    <!-- Puzzles Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">
                <i class="fas fa-puzzle-piece mr-2"></i>
                All Puzzles
            </h2>
        </div>

        @if($puzzles->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puzzle String</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submissions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($puzzles as $puzzle)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $puzzle->id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-mono text-gray-900">{{ $puzzle->puzzle_string }}</div>
                                    <div class="text-xs text-gray-500">{{ strlen($puzzle->puzzle_string) }} letters</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($puzzle->status === 'active')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-play mr-1"></i>
                                            Active
                                        </span>
                                    @elseif($puzzle->status === 'completed')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>
                                            Expired
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $puzzle->submissions_count ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $puzzle->created_at->format('M j, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form action="{{ route('admin.puzzles.destroy', $puzzle) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure you want to delete this puzzle?')"
                                        >Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($puzzles->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $puzzles->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <i class="fas fa-puzzle-piece text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No puzzles yet</h3>
                <p class="text-gray-500">Create your first puzzle to get started!</p>
                <a href="{{ route('admin.puzzles.create') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Create Puzzle
                </a>
            </div>
        @endif
    </div>

@endsection
