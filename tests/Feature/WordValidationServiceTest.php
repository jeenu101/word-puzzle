<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\WordValidationService;

class WordValidationServiceTest extends TestCase
{
    private WordValidationService $wordValidationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wordValidationService = new WordValidationService();
    }

    public function test_is_valid_word_with_valid_words()
    {
        $validWords = ['fox', 'dog', 'cat', 'the', 'and', 'for', 'about', 'animal'];
        
        foreach ($validWords as $word) {
            $this->assertTrue(
                $this->wordValidationService->isValidWord($word),
                "Word '{$word}' should be valid"
            );
        }
    }

    public function test_is_valid_word_with_invalid_words()
    {
        $invalidWords = ['', 'a', 'x', '123', 'fox123', 'fox!'];
        
        foreach ($invalidWords as $word) {
            $this->assertFalse(
                $this->wordValidationService->isValidWord($word),
                "Word '{$word}' should be invalid"
            );
        }
    }

    public function test_get_remaining_letters()
    {
        $testCases = [
            ['fox', 'dgeftoikbvxuaa', 'dgetikbvuaa'],
            ['the', 'thequickbrownfox', 'quickbroownfx'],
            ['cat', 'catdog', 'dog'],
            ['and', 'abcdefghijklmn', 'bcefghijklm'],
        ];

        foreach ($testCases as [$word, $availableLetters, $expectedRemaining]) {
            $result = $this->wordValidationService->getRemainingLetters($word, $availableLetters);
            $this->assertEquals(
                $expectedRemaining,
                $result,
                "Remaining letters for '{$word}' from '{$availableLetters}' should be '{$expectedRemaining}'"
            );
        }
    }

    public function test_calculate_score()
    {
        $testCases = [
            ['fox', 3],
            ['the', 3],
            ['about', 5],
            ['a', 1],
            ['', 0],
        ];

        foreach ($testCases as [$word, $expectedScore]) {
            $result = $this->wordValidationService->calculateScore($word);
            $this->assertEquals(
                $expectedScore,
                $result,
                "Score for '{$word}' should be {$expectedScore}"
            );
        }
    }

    
}
