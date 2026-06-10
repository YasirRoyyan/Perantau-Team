@php
    $likedIds = $likedPostIds ?? [];
    $favoritedIds = $favoritedPostIds ?? [];
    $canDelete = auth()->id() === $post->user_id;
    $isLiked = in_array($post->id, $likedIds);
    $isFavorited = in_array($post->id, $favoritedIds);
    $avatar = $post->user->avatar && \Storage::disk('public')->exists($post->user->avatar)
        ? \Storage::url($post->user->avatar)
        : asset('assets/images/default-avatar.png');
@endphp

<article
    class="dashboard-gallery-card dashboard-gallery-card--post"
    tabindex="0"
    role="button"
    style="background-image: linear-gradient(to bottom, rgba(24, 18, 12, 0.05) 42%, rgba(24, 18, 12, 0.58) 100%), url('{{ \Storage::url($post->image) }}'); background-size: cover; background-position: center; border-radius: 8px; aspect-ratio: 1/1; position: relative; cursor: pointer; transition: transform 0.18s ease, box-shadow 0.18s ease;"
    aria-label="Inspirasi interior dari {{ $post->user->name }}"
    data-post-card
    data-post-id="{{ $post->id }}"
    data-post-owner-id="{{ $post->user_id }}"
    data-post-owner-name="{{ $post->user->name }}"
    data-post-owner-bio="{{ $post->user->bio ?: 'Belum ada bio' }}"
    data-post-caption="{{ $post->caption ?: '' }}"
    data-post-image="{{ \Storage::url($post->image) }}"
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
    <div class="dashboard-gallery-card__label" style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
        <strong>{{ '@' . strtolower($post->user->name) }}</strong>
        <span data-post-like-count>{{ $post->likes_count }} suka</span>
    </div>
</article>
