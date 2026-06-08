<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => ['required', 'string'],
            'caption' => ['nullable', 'string', 'max:1000'],
        ]);

        $imageRawData = $validated['image'];

        if (! preg_match('/^data:image\/png;base64,/', $imageRawData)) {
            return response()->json([
                'success' => false,
                'message' => 'Format gambar desain tidak valid.'
            ], 422);
        }

        $image = preg_replace('/^data:image\/png;base64,/', '', $imageRawData);
        $image = str_replace(' ', '+', $image);
        $decodedImage = base64_decode($image, true);

        if ($decodedImage === false) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membaca gambar desain.'
            ], 422);
        }

        $fileName = 'user_design_' . time() . '_' . Str::random(5) . '.png';
        
        Storage::disk('public')->put('posts/' . $fileName, $decodedImage);
        $caption = trim((string) ($validated['caption'] ?? ''));

        Post::create([
            'user_id' => auth()->id(),
            'image' => 'posts/' . $fileName,
            'caption' => $caption !== '' ? $caption : null,
        ]);

        return response()->json(['success' => true]);
    }

    public function index()
    {
        $allPosts = Post::with('user')->latest()->get();

        return view('interiorgram', ['posts' => $allPosts]);
    }

    public function customRoom()
    {
        $posts = Post::with('user')
            ->latest()
            ->get()
            ->filter(fn ($post) => Storage::disk('public')->exists($post->image))
            ->values();

        return view('pages.custom-room', ['posts' => $posts]);
    }
}
