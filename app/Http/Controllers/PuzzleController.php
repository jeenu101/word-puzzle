<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Puzzle;
use App\Services\PuzzleService;



class PuzzleController extends Controller
{   

    private PuzzleService $puzzleService;

    public function __construct(PuzzleService $puzzleService)
    {
        $this->puzzleService = $puzzleService;
    }

    /**
     * Show all puzzles
     */
    public function index(): View
    {
        $puzzles = Puzzle::withCount('submissions')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('admin.puzzles.index', compact('puzzles'));
    }

    /**
     * Show create puzzle form
     */
    public function create(): View
    {
        return view('admin.puzzles.create');
    }

    /**
     * Store a new puzzle
     */
    public function store(Request $request)
    {
        $request->validate([
            'length' => 'nullable|integer|min:10|max:25',
        ]);

        $length = $request->input('length', 15);

        $puzzleString = $this->puzzleService->generatePuzzleString($length);
        
        $puzzle = Puzzle::create([
            'puzzle_string' => $puzzleString,
            'status' => 'active',
        ]);

        return redirect()->route('admin.puzzles.index')
            ->with('success', 'Puzzle created successfully: ' . $puzzleString);
    }

    /**
     * Delete a puzzle
     */
    public function destroy(Puzzle $puzzle)
    {
        $puzzleString = $puzzle->puzzle_string;
        $puzzle->delete();

        return redirect()->route('admin.puzzles.index')
            ->with('success', 'Puzzle deleted successfully: ' . $puzzleString);
    }
}
