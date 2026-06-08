@php
    $likedIds = $likedPostIds ?? [];
    $favoritedIds = $favoritedPostIds ?? [];
    $canDelete = auth()->id() === $post->user_id;
    $isLiked = in_array($post->id, $likedIds);
    $isFavorited = in_array($post->id, $favoritedIds);
    $avatar = $post->user->avatar && \Storage::disk('public')->exists($post->user->avatar)
        ? asset('storage/' . $post->user->avatar)
        : asset('assets/images/default-avatar.png');
@endphp

<article
    class="dashboard-gallery-card"
    tabindex="0"
    role="button"
    style="background-image: url('{{ asset('storage/' . $post->image) }}'); background-size: cover; background-position: center; border-radius: 8px; aspect-ratio: 1/1; position: relative; cursor: pointer;"
    aria-label="Inspirasi interior dari {{ $post->user->name }}"
    data-post-card
    data-post-id="{{ $post->id }}"
    data-post-owner-id="{{ $post->user_id }}"
    data-post-owner-name="{{ $post->user->name }}"
    data-post-owner-bio="{{ $post->user->bio ?: 'Belum ada bio' }}"
    data-post-caption="{{ $post->caption ?: '' }}"
    data-post-image="{{ asset('storage/' . $post->image) }}"
    data-post-image-alt="Desain interior dari {{ $post->user->name }}"
    data-post-likes="{{ $post->likes_count }}"
    data-post-liked="{{ $isLiked ? 1 : 0 }}"
    data-post-favorited="{{ $isFavorited ? 1 : 0 }}"
    data-post-like-url="{{ route('posts.like', $post) }}"
    data-post-favorite-url="{{ route('posts.favorite', $post) }}"
    data-post-delete-url="{{ route('posts.destroy', $post) }}"
    data-post-can-delete="{{ $canDelete ? 1 : 0 }}"
    data-post-profile-url="{{ route('user.profile', $post->user->name) }}"
    data-post-user-avatar="{{ $avatar }}"
>
    <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.6); padding: 8px; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; color: #fff; font-size: 0.8rem; font-family: sans-serif;">
        <strong>{{ '@' . strtolower($post->user->name) }}</strong>
    </div>
</article>
