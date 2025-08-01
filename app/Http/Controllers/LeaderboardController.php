<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leaderboard;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    /**
     * Show the leaderboard with student scores per puzzle
     */
    public function index(): View
    {
        $leaderboard = Leaderboard::getTopScores(10); // Show top 10 entries
        $statistics = $this->getStatistics();
        
        return view('leaderboard.index', compact('leaderboard', 'statistics'));
    }

    /**
     * Get statistics for the leaderboard
     */
    private function getStatistics(): array
    {
        $leaderboard = Leaderboard::all();
        
        return [
            'total_entries' => $leaderboard->count(),
            'total_students' => $leaderboard->unique('student_id')->count(),
            'total_puzzles' => $leaderboard->unique('puzzle_id')->count(),
            'average_score' => $leaderboard->count() > 0 ? round($leaderboard->avg('total_score'), 1) : 0,
            'highest_score' => $leaderboard->count() > 0 ? $leaderboard->max('total_score') : 0,
            'lowest_score' => $leaderboard->count() > 0 ? $leaderboard->min('total_score') : 0,
        ];
    }
}
