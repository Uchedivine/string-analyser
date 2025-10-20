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
        return response()->json(['message' => 'list endpoint placeholder']);
    }

    public function naturalFilter(Request $request)
    {
        return response()->json(['message' => 'natural language filter placeholder']);
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
