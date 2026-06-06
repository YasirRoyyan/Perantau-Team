<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class NavigationItem extends Model
{
    protected $fillable = [
        'label',
        'route_name',
        'anchor',
        'external_url',
        'auth_state',
        'is_cta',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_cta' => 'boolean',
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
            ['label' => 'Beranda', 'route_name' => 'home', 'anchor' => null, 'external_url' => null, 'auth_state' => 'all', 'is_cta' => false],
            ['label' => 'Cara Kerja', 'route_name' => 'home', 'anchor' => 'cara-kerja', 'external_url' => null, 'auth_state' => 'all', 'is_cta' => false],
            ['label' => 'Kustom Ruangan', 'route_name' => 'custom-room', 'anchor' => null, 'external_url' => null, 'auth_state' => 'all', 'is_cta' => false],
            ['label' => 'Cari Selera mu!', 'route_name' => 'prepare', 'anchor' => null, 'external_url' => null, 'auth_state' => 'all', 'is_cta' => true],
            ['label' => 'Interiorgram', 'route_name' => 'home', 'anchor' => 'interiorgram', 'external_url' => null, 'auth_state' => 'all', 'is_cta' => false],
            ['label' => 'Login', 'route_name' => 'login', 'anchor' => null, 'external_url' => null, 'auth_state' => 'guest', 'is_cta' => false],
            ['label' => 'Dashboard', 'route_name' => 'dashboard', 'anchor' => null, 'external_url' => null, 'auth_state' => 'auth', 'is_cta' => false],
        ];

        try {
            $items = static::active()->get();

            return $items->isNotEmpty() ? $items->toArray() : $fallback;
        } catch (QueryException) {
            return $fallback;
        }
    }
}
