 
 @extends('layouts.app')

 @section('title', 'Admin Dashboard - Word Puzzles')
 
 @section('content')

    <!-- Create Form -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="{{ route('admin.puzzles.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="length" class="block text-sm font-medium text-gray-700 mb-2">
                    Puzzle Length
                </label>
                <input 
                    type="number" 
                    id="length" 
                    name="length" 
                    min="10" 
                    max="25" 
                    value="{{ old('length', 15) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="15"
                >
                <p class="mt-1 text-sm text-gray-500">Number of letters in the puzzle (10-25)</p>
                @error('length')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-blue-50 rounded-lg p-4">
                <h3 class="text-lg font-medium text-blue-800 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>
                    Puzzle Generation Info
                </h3>
                <ul class="text-blue-700 space-y-1 text-sm">
                    <li>• The system will generate a random string of letters</li>
                    <li>• The puzzle will be guaranteed to have at least the minimum number of valid words</li>
                    <li>• Letters will be randomly selected from the alphabet</li>
                    <li>• The puzzle will be set to 'active' status automatically</li>
                </ul>
            </div>

            <div class="flex space-x-4">
                <button 
                    type="submit" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200"
                >
                    <i class="fas fa-magic mr-2"></i>
                    Generate Puzzle
                </button>
                
                <a 
                    href="{{ route('admin.puzzles.index') }}" 
                    class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg text-center transition duration-200"
                >
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
    
@endsection

