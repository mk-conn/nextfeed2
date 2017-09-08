<?php

namespace App\Console\Commands;

use App\Models\Feed;
use App\Models\User;
use Illuminate\Console\Command;

/**
 * Class UpdateFeeds
 *
 * @package App\Console\Commands
 */
class CreateFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feeds:create {url} {user} {folder?}';

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
        $url = $this->argument('url');
        $user = User::findByName($this->argument('user'));
        $folder = $this->argument('folder');
        if (!$folder) {
            $folder = $user->rootFolder();
        }


        $feed = new Feed(['url' => $url]);
        $feed->save();
    }
}
