<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'puzzle_id',
        'student_id',
        'word',
        'score',
        'remaining_letters',
        'is_valid_word',
    ];

    protected $casts = [
        'score' => 'integer',
        'is_valid_word' => 'boolean',
    ];

    /**
     * Get the puzzle that owns the submission
     */
    public function puzzle(): BelongsTo
    {
        return $this->belongsTo(Puzzle::class);
    }

    /**
     * Get the student that owns the submission
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Check if the submission is valid
     */
    public function isValid(): bool
    {
        return $this->is_valid_word;
    }

    /**
     * Get remaining letters as array
     */
    public function getRemainingLettersArray(): array
    {
        return str_split($this->remaining_letters);
    }

    /**
     * Check if there are remaining letters
     */
    public function hasRemainingLetters(): bool
    {
        return !empty($this->remaining_letters);
    }
}
