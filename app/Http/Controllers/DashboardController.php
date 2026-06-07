<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class DashboardController extends Controller
{
    /**
     * Menampilkan Halaman Utama Dashboard / Interiorgram
     */
    public function index()
    {
        // 1. Mengambil data user yang sedang login/aktif
        $user = auth()->user();

        // 2. Menghitung JUMLAH POSTINGAN asli milik user tersebut dari database
        $totalPosts = $user->posts()->count();
        
        // 3. Menghitung total akumulasi suka (default: 0)
        $totalLikes = $user->posts()->sum('likes_count');

        // 4. Mengambil SEMUA postingan terbaru dari database untuk dipajang di galeri utama
        $galleryItems = Post::with('user')->latest()->get();

        // 5. 🛠️ REVISI SOLUSI: Mengarahkan jalur pembacaan view ke dalam folder pages
        return view('pages.dashboard', [
            'user' => $user,
            'totalPosts' => $totalPosts,
            'totalLikes' => $totalLikes,
            'galleryItems' => $galleryItems
        ]);
    }

    /**
     * Memproses Pengunggahan / Upload Postingan Baru dari Pop-up Modal
     */
    public function storePost(Request $request)
    {
        // 1. Validasi input: Gambar wajib diisi, maksimal size 5MB
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'caption' => 'nullable|string|max:1000'
        ]);

        // 2. Cek apakah ada file gambar yang diunggah
        if ($request->hasFile('image')) {
            
            // Simpan file gambar secara fisik ke folder: storage/app/public/posts
            $path = $request->file('image')->store('posts', 'public');

            // 3. Simpan record data baru ke dalam tabel 'posts' di database
            Post::create([
                'user_id' => auth()->id(), // ID user yang sedang login
                'image' => $path,          // Path gambar hasil upload
                'caption' => $request->caption
            ]);

            return redirect()->back()->with('success', 'Inspirasi interior berhasil dibagikan!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah gambar interior.');
    }
}