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
        $usernameInput = $request->username;
        $captionInput = $request->caption;
        $imageRawData = $request->image; 

        $image = str_replace('data:image/png;base64,', '', $imageRawData);
        $image = str_replace(' ', '+', $image);
        $fileName = 'user_design_' . time() . '_' . Str::random(5) . '.png';
        
        Storage::disk('public')->put('posts/' . $fileName, base64_decode($image));

        Post::create([
            'username' => $usernameInput,         
            'caption' => $captionInput,           
            'design_image' => 'posts/' . $fileName 
        ]);

        return response()->json(['success' => true]);
    }

    public function index()
    {
        $allPosts = Post::latest()->get();

        return view('interiorgram', ['posts' => $allPosts]);
    }

    public function customRoom()
    {
        $posts = Post::latest()->get();

        return view('pages.custom-room', ['posts' => $posts]);
    }
}
