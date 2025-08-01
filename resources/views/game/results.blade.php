@extends('layouts.app')

@section('title', 'Game Results - Word Puzzles Game')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Results Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <div class="text-center">
            <i class="fas fa-trophy text-6xl text-yellow-500 mb-4"></i>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Game Complete!</h1>
            <p class="text-lg text-gray-600">Player: <span class="font-semibold">{{ $student->name }}</span></p>
        </div>
    </div>

    <!-- Final Score -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-8 mb-6">
        <div class="text-center text-white">
            <div class="text-6xl font-bold mb-2">{{ $totalScore }}</div>
            <div class="text-xl mb-4">Total Points</div>
            <div class="text-lg">{{ count($wordsUsed) }} words found</div>
        </div>
    </div>

    <!-- Game Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="text-3xl font-bold text-green-600 mb-2">{{ count($wordsUsed) }}</div>
            <div class="text-sm text-gray-600">Words Found</div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="text-3xl font-bold text-blue-600 mb-2">{{ strlen(implode('', $wordsUsed)) }}</div>
            <div class="text-sm text-gray-600">Letters Used</div>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="text-3xl font-bold text-purple-600 mb-2">{{ strlen($remainingLetters) }}</div>
            <div class="text-sm text-gray-600">Letters Remaining</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Words Found -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                Words Found
            </h2>
            
            @if(count($wordsUsed) > 0)
                <div class="space-y-2">
                    @foreach($wordsUsed as $index => $word)
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
                <p class="text-gray-500 italic">No words were found.</p>
            @endif
        </div>

        <!-- Missed Words -->
        @if(count($possibleWords) > 0)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-lightbulb mr-2"></i>
                    Missed Words ({{ count($possibleWords) }})
                </h2>
                
                <div class="bg-yellow-50 rounded-lg p-4 mb-4">
                    <p class="text-yellow-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        These words could have been formed from the remaining letters:
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    @foreach($possibleWords as $word)
                        <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded text-sm">
                            {{ $word }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Unused Letters -->
    @if(!empty($remainingLetters))
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-font mr-2"></i>
                Unused Letters
            </h2>
            
            <div class="bg-gray-100 rounded-lg p-4">
                <div class="text-center">
                    @foreach(str_split($remainingLetters) as $letter)
                        <span class="letter-box unused">{{ strtoupper($letter) }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <a 
                href="{{ route('game.new') }}" 
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-center transition duration-200"
            >
                <i class="fas fa-play mr-2"></i>
                New Game
            </a>
        </div>
    </div>
</div>
@endsection 