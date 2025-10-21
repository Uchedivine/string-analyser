<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStringRequest;
use App\Models\AnalyzedString;
use App\Services\AnalyzerService;
use Illuminate\Http\Request;

class StringController extends Controller
{
    protected AnalyzerService $analyzer;

    public function __construct(AnalyzerService $analyzer)
    {
        $this->analyzer = $analyzer;
    }

    public function store(StoreStringRequest $request)
    {
        $value = $request->input('value');
        $props = $this->analyzer->analyze($value);
        $sha = $props['sha256_hash'];

        if (AnalyzedString::where('sha256_hash', $sha)->exists()) {
            return response()->json(['message' => 'String already exists in the system'], 409);
        }

        $record = AnalyzedString::create([
            'sha256_hash' => $sha,
            'value' => $value,
            'properties' => $props,
        ]);

        return response()->json([
            'id' => $sha,
            'value' => $record->value,
            'properties' => $record->properties,
            'created_at' => $record->created_at->toIso8601String(),
        ], 201);
    }

    public function show($string_value)
    {
        $decoded = urldecode($string_value);

        $record = AnalyzedString::where('sha256_hash', $decoded)
            ->orWhere('value', $decoded)
            ->first();

        if (!$record) {
            return response()->json(['message' => 'String does not exist in the system'], 404);
        }

        return response()->json([
            'id' => $record->sha256_hash,
            'value' => $record->value,
            'properties' => $record->properties,
            'created_at' => $record->created_at->toIso8601String(),
        ]);
    }

   public function index(Request $request)
{
    $query = AnalyzedString::query();

    // Filter for palindrome property
    if ($request->has('is_palindrome')) {
        $isPalindrome = filter_var($request->is_palindrome, FILTER_VALIDATE_BOOLEAN);
        $query->where('properties->is_palindrome', $isPalindrome);
    }

    // Filter for has_numbers property
    if ($request->has('has_numbers')) {
        $hasNumbers = filter_var($request->has_numbers, FILTER_VALIDATE_BOOLEAN);
        $query->where('properties->has_numbers', $hasNumbers);
    }

    // Filter for has_special_characters property
    if ($request->has('has_special_characters')) {
        $hasSpecials = filter_var($request->has_special_characters, FILTER_VALIDATE_BOOLEAN);
        $query->where('properties->has_special_characters', $hasSpecials);
    }

    // Add more filters as needed...

    // Paginate the results
    $results = $query->paginate(15);

    return response()->json($results);
}

    public function naturalFilter(Request $request)
{
    $query = AnalyzedString::query();
    $searchQuery = strtolower($request->input('q', ''));

    if (str_contains($searchQuery, 'palindrome')) {
        $query->where('properties->is_palindrome', true);
    }

    if (str_contains($searchQuery, 'numbers')) {
        $query->where('properties->has_numbers', true);
    }

    if (str_contains($searchQuery, 'special characters')) {
        $query->where('properties->has_special_characters', true);
    }

    // Example for length: "length greater than 10"
    if (preg_match('/length (greater|more) than (\d+)/', $searchQuery, $matches)) {
        $query->where('properties->length', '>', (int)$matches[2]);
    }

    // Example for length: "length less than 10"
     if (preg_match('/length less than (\d+)/', $searchQuery, $matches)) {
        $query->where('properties->length', '<', (int)$matches[2]);
    }

    return response()->json($query->paginate(15));
}

    public function destroy($string_value)
    {
        $decoded = urldecode($string_value);
        $record = AnalyzedString::where('sha256_hash', $decoded)
            ->orWhere('value', $decoded)
            ->first();

        if (!$record) {
            return response()->json(['message' => 'String does not exist in the system'], 404);
        }

        $record->delete();
        return response(null, 204);
    }
}
