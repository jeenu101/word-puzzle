<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the submissions for this student
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Get current active game for this student
     */
    public function getCurrentGame()
    {
        return $this->submissions()
            ->with('puzzle')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Get current puzzle for this student
     */
    public function getCurrentPuzzle()
    {
        // If no submission exists, try to get puzzle from session
        $puzzleId = session("student_{$this->id}_puzzle_id");
        if ($puzzleId) {
            $puzzle = Puzzle::find($puzzleId);
            if ($puzzle && $puzzle->isActive()) {
                return $puzzle;
            }
        }
        
        // If no puzzle found, try to get a random active puzzle
        $puzzle = Puzzle::where('status', 'active')->inRandomOrder()->first();
        
        return $puzzle;
    }

    /**
     * Find a puzzle that the student hasn't played yet
     */
    public function findUnplayedPuzzle(): ?Puzzle
    {
        // Get all active puzzles
        $activePuzzles = Puzzle::where('status', 'active')->get();
        
        // Get puzzle IDs that the student has already played
        $playedPuzzleIds = $this->submissions()
            ->distinct()
            ->pluck('puzzle_id')
            ->toArray();
        
        // Find puzzles that the student hasn't played
        $unplayedPuzzles = $activePuzzles->filter(function ($puzzle) use ($playedPuzzleIds) {
            return !in_array($puzzle->id, $playedPuzzleIds);
        });
        
        // Return a random unplayed puzzle, or null if none found
        return $unplayedPuzzles->isNotEmpty() ? $unplayedPuzzles->random() : null;
    }
}
