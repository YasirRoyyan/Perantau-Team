<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostLikeUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $postId,
        public int $postOwnerId,
        public int $likesCount,
        public bool $liked,
        public int $actorId,
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('interiology.dashboard'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'post.like.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'post_id' => $this->postId,
            'post_owner_id' => $this->postOwnerId,
            'likes_count' => $this->likesCount,
            'liked' => $this->liked,
            'actor_id' => $this->actorId,
        ];
    }
}
