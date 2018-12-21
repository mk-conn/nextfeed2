<?php

namespace App\Providers;


use App\Models\Feed;
use App\Models\Folder;
use App\Observers\FeedObserver;
use App\Observers\FolderObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Feed::observe(FeedObserver::class);
        Folder::observe(FolderObserver::class);
    }
}
