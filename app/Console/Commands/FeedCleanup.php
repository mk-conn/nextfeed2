<?php

namespace App\Console\Commands;


use App\Models\Feed;
use Illuminate\Console\Command;

/**
 * Class DatabaseCleaner
 *
 * @package App\Console\Commands
 */
class FeedCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:cleanup 
        {--id=* : ID of feed to clean [defaults to all]}
        {--days=10 : Articles older than [--days]}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old articles';

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
        $this->info('Cleaning feed articles...');
        $feeds = collect([]);
        $id = $this->option('id');
        $days = $this->option('days');

        if (!$id) {
            $feeds = Feed::all();
        } else {
            $feed = Feed::find($id);
            if ($feed) {
                $feeds->push($feed);
            }
        }

        $feeds->each(function (Feed $feed) use ($days) {
            $this->info('Cleaning ' . $feed->name);
            $count = $feed->cleanup($days);
            $this->output->writeln('<comment>Cleaned ' . $count . '</comment>');
        });
    }
}
