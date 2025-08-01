<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Student;
use App\Models\Submission;
use App\Models\Puzzle;
use App\Services\WordValidationService;
use App\Services\PuzzleService;
use App\Models\Leaderboard;

class GameController extends Controller
{
    private $wordValidationService;
    private $puzzleService;

    public function __construct(
        WordValidationService $wordValidationService,
        PuzzleService $puzzleService
    )
    {
        $this->wordValidationService = $wordValidationService;
        $this->puzzleService = $puzzleService;
    }

    /**
     * Show the main game interface
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        
        if ($user->isStudent()) {
            $student = $user->student;
            
            $puzzle = $student->getCurrentPuzzle();
            if (!$puzzle || !$puzzle->isActive()) {
                return redirect()->route('game.index')->with('error', 'Puzzle is not active.');
            }

            // Store the puzzle ID in session for this student
            $request->session()->put("student_{$student->id}_puzzle_id", $puzzle->id);

            $progress = $this->getStudentProgress($student, $puzzle);
            
            return view('game.play', compact('puzzle', 'progress', 'student', 'user'));
        }

        return view('welcome');
    }

    /**
     * Submit a word
     */
    public function submitWord(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'word' => 'required|string|max:255',
        ]);

        $student = $user->student;

        $puzzle = $student->getCurrentPuzzle();
        if (!$puzzle || !$puzzle->isActive()) {
            return redirect()->route('game.index')->with('error', 'Puzzle is not active.');
        }

        $word = strtolower(trim($request->word));

        // Get the last submission for this student and puzzle to get remaining letters
        $lastSubmission = $student->submissions()
            ->where('puzzle_id', $puzzle->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $availableLetters = $lastSubmission ? $lastSubmission->remaining_letters : $puzzle->puzzle_string;

        // Validate the word
        $isValidWord = $this->wordValidationService->isValidWord($word);
        $canFormWord = $this->wordValidationService->canFormWord($word, $availableLetters);

        if (!$isValidWord) {
            return redirect()->route('game.index')->with('error', 'Invalid English word: ' . $word);
        }

        if (!$canFormWord) {
            return redirect()->route('game.index')->with('error', 'Word cannot be formed from available letters: ' . $word);
        }

        // Check if word was already used by this student
        $existingSubmission = $student->submissions()
            ->where('puzzle_id', $puzzle->id)
            ->where('word', $word)
            ->first();

        if ($existingSubmission) {
            return redirect()->route('game.index')->with('error', 'Word already used: ' . $word);
        }

        // Calculate score and remaining letters
        $score = $this->wordValidationService->calculateScore($word);
        $remainingLetters = $this->wordValidationService->getRemainingLetters($word, $availableLetters);

        // Create submission
        $submission = $student->submissions()->create([
            'puzzle_id' => $puzzle->id,
            'word' => $word,
            'score' => $score,
            'remaining_letters' => $remainingLetters,
            'is_valid_word' => true,
        ]);

        // Update leaderboard for this student and puzzle
        Leaderboard::updateStudentPuzzleScore($student->id, $puzzle->id);

        $message = "Word submitted: '{$word}' (+{$score} points)";

        return redirect()->route('game.index')->with('success', $message);
    }

    /**
     * Get student progress data
     */
    private function getStudentProgress(Student $student, Puzzle $puzzle): array
    {
        $submissions = $student->submissions()
            ->where('puzzle_id', $puzzle->id)
            ->where('is_valid_word', true)
            ->orderBy('created_at', 'asc')
            ->get();

        $totalScore = $submissions->sum('score');
        $wordsUsed = $submissions->pluck('word')->toArray();
        $remainingLetters = $submissions->last() ? $submissions->last()->remaining_letters : $puzzle->puzzle_string;

        // Find possible words from remaining letters
        $possibleWords = $this->wordValidationService->findPossibleWords($remainingLetters);

        return [
            'total_score' => $totalScore,
            'words_used' => $wordsUsed,
            'words_count' => count($wordsUsed),
            'remaining_letters' => $remainingLetters,
            'can_continue' => !empty($remainingLetters),
            'possible_words_count' => count($possibleWords),
            'possible_words' => $possibleWords,
            'submissions' => $submissions,
        ];
    }

    /**
     * Show game progress
     */
    public function progress(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        $puzzle = $student->getCurrentPuzzle();
        if (!$puzzle) {
            return redirect()->route('game.index');
        }

        $progress = $this->getStudentProgress($student, $puzzle);
        
        return view('game.progress', compact('puzzle', 'progress', 'student', 'user'));
    }

    /**
     * End the game
     */
    public function endGame(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        $puzzle = $student->getCurrentPuzzle();
        if (!$puzzle) {
            return redirect()->route('game.index')->with('error', 'Puzzle not found.');
        }

        $submissions = $student->submissions()
            ->where('puzzle_id', $puzzle->id)
            ->where('is_valid_word', true)
            ->orderBy('created_at', 'asc')
            ->get();

        $totalScore = $submissions->sum('score');
        $wordsUsed = $submissions->pluck('word')->toArray();
        $remainingLetters = $submissions->last() ? $submissions->last()->remaining_letters : $puzzle->puzzle_string;

        // Find possible words that were missed
        $possibleWords = $this->wordValidationService->findPossibleWords($remainingLetters);

        // Update leaderboard with final score
        Leaderboard::updateStudentPuzzleScore($student->id, $puzzle->id);

        return view('game.results', compact('puzzle', 'student', 'totalScore', 'wordsUsed', 'remainingLetters', 'possibleWords', 'user'));
    }

    /**
     * Start a new game
     */
    public function newGame(Request $request)
    {
        $user = Auth::user();
        
        $student = $user->student;
        // Find a puzzle that the student hasn't played yet
        $puzzle = $student->findUnplayedPuzzle();
        
        // If no unplayed puzzle found, create a new one
        if (!$puzzle) {
            $puzzle = Puzzle::create([
                'puzzle_string' => $this->puzzleService->generatePuzzleString(),
                'status' => 'active',
            ]);
        }

        // Store the puzzle ID in session for this student
        $request->session()->put("student_{$student->id}_puzzle_id", $puzzle->id);

        return redirect()->route('game.index');
    }
}
