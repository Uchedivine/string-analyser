<?php

namespace App\Services;

class AnalyzerService
{
    public function analyze(string $value): array
    {
        $length = mb_strlen($value);

        // Check palindrome (case-insensitive)
        $normalized = mb_strtolower($value);
        $isPalindrome = $this->mb_strrev($normalized) === $normalized;

        // Split into characters (unicode-safe)
        $chars = $this->mb_str_split($value);

        $uniqueChars = count(array_unique($chars));

        // Word count (split by whitespace)
        $trimmed = trim($value);
        $wordCount = $trimmed === '' ? 0 : count(preg_split('/\s+/u', $trimmed));

        // sha256 hash
        $sha256 = hash('sha256', $value);
        $hasNumbers = preg_match('/\d/', $value) ? true : false;
$hasSpecials = preg_match('/[^A-Za-z0-9\s]/u', $value) ? true : false;


        // Character frequency map
        $freq = [];
        foreach ($chars as $c) {
            $freq[$c] = ($freq[$c] ?? 0) + 1;
        }
return [
    'length' => $length,
    'is_palindrome' => $isPalindrome,
    'unique_characters' => $uniqueChars,
    'word_count' => $wordCount,
    'sha256_hash' => $sha256,
    'has_numbers' => $hasNumbers,
    'has_special_characters' => $hasSpecials,
    'character_frequency_map' => $freq,
];

    }

    private function mb_str_split(string $str): array
    {
        preg_match_all('/./us', $str, $matches);
        return $matches[0];
    }

    private function mb_strrev(string $str): string
    {
        preg_match_all('/./us', $str, $matches);
        return join('', array_reverse($matches[0]));
    }
}
