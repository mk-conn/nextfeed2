<?php

namespace App\Console\Commands;

use App\Models\Feed;
use Illuminate\Console\Command;

/**
 * Class UpdateFeeds
 *
 * @package App\Console\Commands
 */
class FetchFeedArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feeds:articles
        {--id=* : ID of feed to update}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update feed(s)';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Updating feeds...');
        $feeds = collect([]);
        $id = $this->option('id');

        if (!$id) {
            $feeds = Feed::all();
        } else {
            $feed = Feed::find($id);
            if ($feed) {
                $feeds->push($feed);
            }
        }

        $feeds->each(function (Feed $feed) {
            $this->info('Updating ' . $feed->name);
            $feed->fetchNewArticles();
            $this->info('Updated ' . $feed->name);
        });
    }
}
