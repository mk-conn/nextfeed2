<?php

namespace App\Console\Commands;


use App\BaseModel;
use App\Models\Article;
use App\Models\Feed;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Class DatabaseCleaner
 *
 * @package App\Console\Commands
 */
class DatabaseCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feeds:clean 
        {--id=* : ID of feed to clean (Empty for all)}
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
        $dateFormat = BaseModel::dateFormat();
        $maxUpatedDate = Carbon::create()
                               ->subDays($days)
                               ->format($dateFormat);

        if (!$id) {
            $feeds = Feed::all();
        } else {
            $feed = Feed::find($id);
            if ($feed) {
                $feeds->push($feed);
            }
        }

        $feeds->each(function (Feed $feed) use ($maxUpatedDate) {
            $this->info('<info>Cleaning ' . $feed->name . '</info>');
            $count = Article::where('updated_at', '<', $maxUpatedDate)
                            ->where('feed_id', $feed->id)
                            ->delete();
            $this->info('<success>Cleaned ' . $count . '</success>');
        });
    }
}
