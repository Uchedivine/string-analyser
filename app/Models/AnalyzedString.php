<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyzedString extends Model
{
    protected $table = 'analyzed_strings';

    protected $fillable = ['sha256_hash', 'value', 'properties'];

    protected $casts = [
        'properties' => 'array',
    ];
}
