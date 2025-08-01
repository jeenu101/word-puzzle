@extends('layouts.app')

@section('title', 'Game Progress - Word Puzzles Game')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Progress Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Game Progress</h1>
                <p class="text-gray-600">Player: <span class="font-semibold">{{ $student->name }}</span></p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-blue-600">{{ $progress['total_score'] }}</div>
                <div class="text-sm text-gray-500">Total Score</div>
            </div>
        </div>
    </div>

    <!-- Remaining Letters -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
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
    </div>

    <!-- Possible Words -->
    @if($progress['possible_words_count'] > 0)
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-lightbulb mr-2"></i>
                Possible Words ({{ $progress['possible_words_count'] }})
            </h2>
            
            <div class="bg-yellow-50 rounded-lg p-4 mb-4">
                <p class="text-yellow-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Hint:
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                @php $i = 0; @endphp
                @foreach ($progress['possible_words'] as $word)
                    @if ($i++ == 5)
                        @break
                    @endif
                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded text-sm">
                        {{ $word }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Submission History -->
    @if($progress['submissions']->count() > 0)
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-history mr-2"></i>
                Submission History
            </h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Word</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remaining Letters</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($progress['submissions'] as $index => $submission)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="word-badge">{{ $submission->word }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="score-badge">+{{ $submission->score }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $submission->remaining_letters }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $submission->created_at->format('H:i:s') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <a 
                href="{{ route('game.index') }}" 
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-center transition duration-200"
            >
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Game
            </a>
            
            @if($progress['can_continue'])
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
            @else
                <a 
                    href="{{ route('game.index') }}" 
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-center transition duration-200"
                >
                    <i class="fas fa-play mr-2"></i>
                    Play Again
                </a>
            @endif
            
            <a 
                href="{{ route('leaderboard.index') }}" 
                class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-6 rounded-lg text-center transition duration-200"
            >
                <i class="fas fa-trophy mr-2"></i>
                View Leaderboard
            </a>
        </div>
    </div>
</div>
@endsection 