<?php

namespace App\Console\Commands;


use App\Models\Feed;
use App\Models\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;

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
    protected $description = 'Discover and store a feed';

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
        $user = User::whereName($this->argument('user'))
                    ->first();
        $folder = $this->argument('folder');

        if (!$user) {
            throw new InvalidArgumentException("Invalid user.");
        }


        $feed = new Feed(['url' => $url]);
        $feed->user()
             ->associate($user);

        if ($folder) {
            $feed->folder()
                 ->associate($folder);
        }

        $feed->save();
    }
}
