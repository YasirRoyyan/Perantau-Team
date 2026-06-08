<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'image', 'caption'];

    // Relasi: Postingan ini milik seorang User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_likes')->withTimestamps();
    }

    public function favoriters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_favorites')->withTimestamps();
    }

}
