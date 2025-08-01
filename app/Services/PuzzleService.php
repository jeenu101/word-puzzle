<?php

namespace App\Services;

class PuzzleService
{
    private $wordValidationService;
    
    public function __construct(WordValidationService $wordValidationService)
    {
        $this->wordValidationService = $wordValidationService;
    }

    /**
     * Generate a random puzzle string that guarantees at least one valid word
     */
    public function generatePuzzleString(int $length = 15): string
    {
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        $puzzleString = '';
        
        // Generate random string
        for ($i = 0; $i < $length; $i++) {
            $puzzleString .= $letters[rand(0, strlen($letters) - 1)];
        }
        
        // Ensure at least one valid word can be formed
        $possibleWords = $this->wordValidationService->findPossibleWords($puzzleString);
        
        if (empty($possibleWords)) {
            // If no words possible, add some common letter combinations
            $puzzleString = $this->generatePuzzleString($length);
        }
        
        return $puzzleString;
    }

    
} 