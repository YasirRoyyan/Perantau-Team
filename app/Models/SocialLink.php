<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class SocialLink extends Model
{
    protected $fillable = [
        'label',
        'url',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public static function activeOrFallback(): array
    {
        $fallback = [
            ['label' => 'WhatsApp', 'url' => '#', 'icon' => 'assets/icons/wa.png'],
            ['label' => 'Instagram', 'url' => '#', 'icon' => 'assets/icons/ig.png'],
        ];

        try {
            $links = static::active()->get();

            return $links->isNotEmpty() ? $links->toArray() : $fallback;
        } catch (QueryException) {
            return $fallback;
        }
    }
}
