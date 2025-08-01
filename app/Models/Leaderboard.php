<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leaderboard extends Model
{
    use HasFactory;

    protected $table = 'leaderboards';

    protected $fillable = [
        'student_id',
        'puzzle_id',
        'total_score',
        'words_used',
    ];

    protected $casts = [
        'total_score' => 'integer',
        'words_used' => 'array',
    ];

    /**
     * Get the student that owns the leaderboard entry
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the puzzle that owns the leaderboard entry
     */
    public function puzzle(): BelongsTo
    {
        return $this->belongsTo(Puzzle::class);
    }

    /**
     * Get the top 10 highest scores
     */
    public static function getTopScores(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return static::with(['student', 'puzzle'])
            ->orderBy('total_score', 'desc')
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Update or create leaderboard entry for a student and puzzle
     */
    public static function updateStudentPuzzleScore(int $studentId, int $puzzleId): void
    {
        // Get all valid submissions for this student and puzzle
        $submissions = Submission::where('student_id', $studentId)
            ->where('puzzle_id', $puzzleId)
            ->where('is_valid_word', true)
            ->get();
        

        $totalScore = $submissions->sum('score');
        $wordsUsed = $submissions->pluck('word')->toArray();

        static::updateOrCreate(
            [
                'student_id' => $studentId,
                'puzzle_id' => $puzzleId,
            ],
            [
                'student_name' => 'no name',
                'total_score' => $totalScore,
                'words_used' => $wordsUsed,
            ]
        );
    }

    /**
     * Get leaderboard for a specific puzzle
     */
    public static function getPuzzleLeaderboard(int $puzzleId, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return static::with(['student', 'puzzle'])
            ->where('puzzle_id', $puzzleId)
            ->orderBy('total_score', 'desc')
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get leaderboard for a specific student
     */
    public static function getStudentLeaderboard(int $studentId): \Illuminate\Database\Eloquent\Collection
    {
        return static::with(['student', 'puzzle'])
            ->where('student_id', $studentId)
            ->orderBy('total_score', 'desc')
            ->get();
    }

    /**
     * Check if a word is already used by a student
     */
    public function hasWord(string $word): bool
    {
        return in_array(strtolower($word), array_map('strtolower', $this->words_used ?? []));
    }

    /**
     * Add a word to the student's used words
     */
    public function addWord(string $word): void
    {
        $wordsUsed = $this->words_used ?? [];
        $wordsUsed[] = strtolower($word);
        $this->update(['words_used' => $wordsUsed]);
    }
}
