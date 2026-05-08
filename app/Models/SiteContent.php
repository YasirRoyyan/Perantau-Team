<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class SiteContent extends Model
{
    protected $fillable = [
        'key',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    public static function payload(string $key, array $fallback = []): array
    {
        try {
            return static::where('key', $key)->value('payload') ?? $fallback;
        } catch (QueryException) {
            return $fallback;
        }
    }
}
