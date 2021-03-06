<?php

namespace App\Console\Commands;

use App\Models\Feed;
use Illuminate\Console\Command;

/**
 * Class UpdateFeeds
 *
 * @package App\Console\Commands
 */
class ArticlesFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:fetch
        {--id=* : ID of feed to update [defaults to all]}
    ';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update articles of a feed (or all)';
    
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
        $id = $this->option('id');
        
        if ($id) {
            $feeds = Feed::whereIn('id', [$id])
                         ->get();
        } else {
            $feeds = Feed::all();
        }
        
        $feeds->each(
            function (Feed $feed) {
                $this->info('Updating ' . $feed->name);
                $saved = $feed->fetchNewArticles();
                $this->info('Updated ' . $feed->name . ' - ' . $saved . ' new/updated articles');
            });
    }
}
