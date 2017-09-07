<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class UpdateFeeds
 *
 * @package App\Console\Commands
 */
class UpdateFeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feeds:update
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
    }
}
