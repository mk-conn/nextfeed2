<?php

namespace App\Events;

use App\Models\Feed;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ArticlesFetched implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Feed
     */
    public $feed;

    /**
     * Create a new event instance.
     *
     * @param Feed $feed
     */
    public function __construct(Feed $feed)
    {
        $this->feed = $feed;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.User.' . $this->feed->user->id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->feed->id
        ];
    }

    public function broadcastAs()
    {
        return 'feed.articles.fetched';
    }
}
