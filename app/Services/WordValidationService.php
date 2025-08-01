<?php

namespace App\Services;

class WordValidationService
{
    private array $englishWords = [
        // Common 2-letter words
        'an', 'as', 'at', 'be', 'by', 'do', 'go', 'he', 'if', 'in', 'is', 'it', 'me', 'my', 'no', 'of', 'on', 'or', 'so', 'to', 'up', 'us', 'we',
        
        // Common 3-letter words
        'and', 'are', 'but', 'can', 'did', 'for', 'get', 'had', 'has', 'her', 'him', 'his', 'how', 'its', 'let', 'man', 'new', 'not', 'now', 'old', 'one', 'our', 'out', 'put', 'say', 'see', 'she', 'the', 'too', 'two', 'use', 'was', 'way', 'who', 'you',
        'fox', 'dog', 'cat', 'hat', 'bat', 'rat', 'mat', 'sat', 'fat', 'pat', 'get', 'let', 'set', 'bet', 'wet', 'jet', 'net', 'pet', 'yet', 'met',
        'big', 'dig', 'fig', 'pig', 'rig', 'wig', 'jig', 'zig', 'bin', 'din', 'fin', 'gin', 'kin', 'pin', 'sin', 'tin', 'win', 'yin', 'zip',
        'box', 'cox', 'fox', 'lox', 'pox', 'sox', 'tax', 'wax', 'max', 'fax', 'nix', 'mix', 'six', 'fix', 'vix', 'zoo', 'too', 'two', 'who', 'how', 'now', 'cow', 'bow', 'row', 'sow', 'tow', 'low', 'mow', 'wow',
        'eat', 'bat', 'cat', 'fat', 'hat', 'mat', 'pat', 'rat', 'sat', 'vat', 'wet', 'get', 'let', 'met', 'net', 'pet', 'set', 'yet', 'bet', 'jet',
        'run', 'bun', 'fun', 'gun', 'nun', 'pun', 'sun', 'tun', 'cut', 'but', 'gut', 'hut', 'jut', 'nut', 'put', 'rut', 'tut', 'out', 'put', 'but', 'cut', 'gut', 'hut', 'jut', 'nut', 'rut', 'tut',
        
        // Common 4-letter words
        'about', 'after', 'also', 'away', 'back', 'been', 'come', 'each', 'even', 'from', 'give', 'good', 'have', 'here', 'just', 'know', 'like', 'look', 'make', 'many', 'more', 'most', 'much', 'must', 'name', 'need', 'only', 'over', 'part', 'said', 'same', 'seem', 'some', 'take', 'tell', 'than', 'that', 'them', 'then', 'they', 'this', 'time', 'very', 'want', 'well', 'went', 'were', 'what', 'when', 'will', 'with', 'word', 'work', 'year', 'your',
        'book', 'look', 'cook', 'hook', 'took', 'room', 'zoom', 'boom', 'doom', 'groom', 'broom',
        'play', 'stay', 'day', 'way', 'say', 'may', 'pay', 'ray', 'bay', 'gay', 'hay', 'jay', 'lay', 'nay', 'quay', 'ray', 'sly', 'toy', 'buy', 'cry', 'dry', 'fly', 'fry', 'guy', 'shy', 'sky', 'try', 'why',
        
        // Common 5-letter words
        'about', 'after', 'again', 'along', 'always', 'another', 'around', 'because', 'before', 'below', 'between', 'bring', 'came', 'could', 'every', 'first', 'found', 'great', 'house', 'large', 'might', 'never', 'other', 'place', 'right', 'should', 'small', 'sound', 'still', 'their', 'there', 'these', 'thing', 'think', 'three', 'through', 'under', 'until', 'water', 'where', 'which', 'while', 'world', 'would', 'write', 'years',
        
        // Common 6-letter words
        'always', 'around', 'before', 'better', 'change', 'family', 'father', 'follow', 'friend', 'ground', 'happen', 'letter', 'mother', 'number', 'people', 'picture', 'school', 'second', 'should', 'something', 'sometimes', 'through', 'together', 'without', 'animal',
        
        // Common 7+ letter words
        'because', 'between', 'children', 'different', 'example', 'important', 'question', 'something', 'sometimes', 'together', 'without'
    ];

    /**
     * Check if a word is a valid English word
     */
    public function isValidWord(string $word): bool
    {
        $word = strtolower(trim($word));
        
        // Basic validation
        if (empty($word) || strlen($word) < 2) {
            return false;
        }
        
        // Check if word contains only letters (after trimming)
        if (!ctype_alpha($word)) {
            return false;
        }
        
        // Check against our dictionary (all words in dictionary are lowercase)
        return in_array($word, $this->englishWords);
    }

    /**
     * Check if a word can be formed from the given letters
     */
    public function canFormWord(string $word, string $availableLetters): bool
    {
        $word = strtolower(trim($word));
        $availableLetters = strtolower(trim($availableLetters));
        
        if (!$this->isValidWord($word)) {
            return false;
        }
        
        $wordLetters = str_split($word); //splits the string to array
        $availableLetterCounts = array_count_values(str_split($availableLetters));
        
        foreach ($wordLetters as $letter) {
            if (!isset($availableLetterCounts[$letter]) || $availableLetterCounts[$letter] <= 0) {
                return false;
            }
            $availableLetterCounts[$letter]--;
        }
        
        return true;
    }

    /**
     * Get remaining letters after using a word
     */
    public function getRemainingLetters(string $word, string $availableLetters): string
    {
        $word = strtolower(trim($word));
        $availableLetters = strtolower(trim($availableLetters));
        
        if (!$this->canFormWord($word, $availableLetters)) {
            return $availableLetters;
        }
        
        $wordLetters = str_split($word);
        $availableLetterCounts = array_count_values(str_split($availableLetters));
        
        foreach ($wordLetters as $letter) {
            $availableLetterCounts[$letter]--;
        }
        
        $remaining = '';
        foreach ($availableLetterCounts as $letter => $count) {
            $remaining .= str_repeat($letter, $count);
        }
        
        return $remaining;
    }

    /**
     * Calculate score for a word (1 point per letter)
     */
    public function calculateScore(string $word): int
    {
        return strlen(trim($word));
    }

    /**
     * Find possible words from available letters
     */
    public function findPossibleWords(string $availableLetters): array
    {
        $availableLetters = strtolower(trim($availableLetters));
        $possibleWords = [];
        
        foreach ($this->englishWords as $word) {
            if ($this->canFormWord($word, $availableLetters)) {
                $possibleWords[] = $word;
            }
        }
        
        // Sort by length (longest first)
        usort($possibleWords, function($a, $b) {
            return strlen($b) - strlen($a);
        });
        
        return $possibleWords;
    }
} 