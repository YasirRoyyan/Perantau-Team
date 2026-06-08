<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostFavorite;
use App\Models\PostLike;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostInteractionController extends Controller
{
    public function toggleLike(Request $request, Post $post): JsonResponse
    {
        $userId = $request->user()->id;

        $result = DB::transaction(function () use ($post, $userId) {
            $lockedPost = Post::whereKey($post->id)->lockForUpdate()->firstOrFail();

            $like = PostLike::where('post_id', $post->id)
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->first();

            if ($like) {
                $like->delete();
                $lockedPost->decrement('likes_count');

                return ['liked' => false];
            }

            PostLike::create([
                'post_id' => $post->id,
                'user_id' => $userId,
            ]);

            $lockedPost->increment('likes_count');

            return ['liked' => true];
        });

        $post->refresh();

        return response()->json([
            'success' => true,
            'liked' => $result['liked'],
            'likes_count' => (int) $post->likes_count,
        ]);
    }

    public function toggleFavorite(Request $request, Post $post): JsonResponse
    {
        $userId = $request->user()->id;

        $favorite = PostFavorite::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($favorite) {
            $favorite->delete();

            return response()->json([
                'success' => true,
                'favorited' => false,
            ]);
        }

        PostFavorite::create([
            'post_id' => $post->id,
            'user_id' => $userId,
        ]);

        return response()->json([
            'success' => true,
            'favorited' => true,
        ]);
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        if ($post->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu hanya bisa menghapus postingan milikmu sendiri.',
            ], 403);
        }

        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
