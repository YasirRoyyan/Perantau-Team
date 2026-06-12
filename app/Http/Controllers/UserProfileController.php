<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostFavorite;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     * Menampilkan Halaman Profil Pengguna Lain / Spesifik secara Dinamis
     */
    public function show($username)
    {
        // 1. Cari user berdasarkan nama di database, jika tidak ada langsung return 404
        $user = User::where('name', $username)->firstOrFail();

        // 2. Hitung jumlah postingan asli milik user tersebut dari database
        $totalPosts = $user->posts()->count();

        // 3. Hitung total akumulasi likes yang didapatkan oleh postingan user ini
        $totalLikes = $user->posts()->sum('likes_count');

        // 4. Ambil semua postingan milik user ini untuk dipajang di grid bawah profilnya
        $posts = $user->posts()
            ->latest()
            ->get()
            ->values();
        $likedPostIds = PostLike::where('user_id', auth()->id())->pluck('post_id')->all();
        $favoritedPostIds = PostFavorite::where('user_id', auth()->id())->pluck('post_id')->all();
        $savedPosts = $user->id === auth()->id()
            ? Post::with('user')
                ->whereIn('id', $favoritedPostIds)
                ->latest()
                ->get()
                ->values()
            : collect();

        // 5. Kirim data ke file blade user-profile
        return view('pages.user-profile', [
            'user' => $user,
            'totalPosts' => $totalPosts,
            'totalLikes' => $totalLikes,
            'posts' => $posts,
            'likedPostIds' => $likedPostIds,
            'favoritedPostIds' => $favoritedPostIds,
            'savedPosts' => $savedPosts,
        ]);
    }
}
