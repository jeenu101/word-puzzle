<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Puzzle extends Model
{
    use HasFactory;

    protected $fillable = [
        'puzzle_string',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get submissions for this puzzle
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Check if puzzle is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

}