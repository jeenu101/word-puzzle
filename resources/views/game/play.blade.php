@extends('layouts.app')

@section('title', 'Play - Word Puzzles Game')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Game Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Word Puzzle Game</h1>
                <p class="text-gray-600">Player: <span class="font-semibold">{{ $student->name }}</span></p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-blue-600">{{ $progress['total_score'] }}</div>
                <div class="text-sm text-gray-500">Total Score</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Available Letters -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-font mr-2"></i>
                Available Letters
            </h2>
            
            <div class="bg-gray-100 rounded-lg p-4">
                <div class="text-center">
                    @foreach(str_split($progress['remaining_letters']) as $letter)
                        <span class="letter-box available">{{ strtoupper($letter) }}</span>
                    @endforeach
                </div>
                <div class="text-center mt-2">
                    <span class="text-sm text-gray-600">{{ strlen($progress['remaining_letters']) }} letters remaining</span>
                </div>
            </div>

            <!-- Word Submission Form -->
            <form action="{{ route('game.submit') }}" method="POST" class="mt-6">
                @csrf
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        name="word" 
                        placeholder="Enter a word..." 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                        maxlength="50"
                        pattern="[a-zA-Z]+"
                        title="Please enter only letters"
                    >
                    <button 
                        type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit
                    </button>
                </div>
            </form>
        </div>

        <!-- Game Stats -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-chart-bar mr-2"></i>
                Game Statistics
            </h2>

            <!-- Current Score -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $progress['total_score'] }}</div>
                    <div class="text-sm text-blue-700">Total Score</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $progress['words_count'] }}</div>
                    <div class="text-sm text-green-700">Words Found</div>
                </div>
            </div>

            <!-- Words Used -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-800 mb-3">Words Found:</h3>
                @if(count($progress['words_used']) > 0)
                    <div class="space-y-2">
                        @foreach($progress['words_used'] as $index => $word)
                            <div class="flex justify-between items-center p-2 bg-green-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="text-sm text-gray-500 mr-2">#{{ $index + 1 }}</span>
                                    <span class="word-badge">{{ $word }}</span>
                                </div>
                                <span class="score-badge">+{{ strlen($word) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic">No words submitted yet.</p>
                @endif
            </div>

            <!-- Possible Words -->
            @if($progress['possible_words_count'] > 0)
                <div class="bg-yellow-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-yellow-800 mb-2">
                        <i class="fas fa-lightbulb mr-2"></i>
                        Possible Words ({{ $progress['possible_words_count'] }})
                    </h3>
                    <p class="text-sm text-yellow-700">
                        These words can still be formed from the remaining letters.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Game Actions -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <a 
                href="{{ route('game.progress') }}" 
                class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg text-center transition duration-200"
            >
                <i class="fas fa-chart-line mr-2"></i>
                View Progress
            </a>
            
            <form action="{{ route('game.end') }}" method="POST" class="flex-1">
                @csrf
                <button 
                    type="submit" 
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200"
                >
                    <i class="fas fa-stop mr-2"></i>
                    End Game
                </button>
            </form>
            
            <a 
                href="{{ route('leaderboard.index') }}" 
                class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-lg text-center transition duration-200"
            >
                <i class="fas fa-trophy mr-2"></i>
                Leaderboard
            </a>
        </div>
    </div>
</div>
@endsection 