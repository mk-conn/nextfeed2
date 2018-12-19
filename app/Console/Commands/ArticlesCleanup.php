<?php

namespace App\Console\Commands;


use App\Models\Feed;
use Illuminate\Console\Command;

/**
 * Class DatabaseCleaner
 *
 * @package App\Console\Commands
 */
class ArticlesCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:cleanup 
        {--id=* : ID of feed to clean [defaults to all]}
        {--days=0 : Articles older than [--days]}
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
            $settings = $feed->settings;
            if (isset($settings[ 'articles' ][ 'keep' ]) && !$days) {
                $days = $settings[ 'articles' ][ 'keep' ];
            }
            $this->info('Cleaning ' . $feed->name . ' older than ' . $days. ' days');
            $count = $feed->cleanup($days, $force = true);
            $this->output->writeln('<comment>Cleaned ' . $count . '</comment>');
        });
    }
}
