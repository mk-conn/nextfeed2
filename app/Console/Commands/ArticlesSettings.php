<?php

namespace App\Console\Commands;

use App\Models\Feed;
use Illuminate\Console\Command;

class ArticlesSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:settings
        {--id=* : ID of feed to update [defaults to all]}
        {--setting= : Setting to be ... well, set.}
        {--value= : Value to be set}
    ';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update article settings for feeds';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     *
     */
    public function handle()
    {
        $this->info('Updating feeds...');
        $id = $this->option('id');
        $setting = $this->option('setting');
        $value = $this->option('value');
        
        if (!$setting) {
            $setting = $this->ask('Whats the setting property?');
        }
        if (!$value) {
            $value = $this->ask('Whats the value?');
        }
        
        if ($id) {
            $feeds = Feed::whereIn('id', [$id])
                         ->get();
        } else {
            $feeds = Feed::all();
        }
        $feeds->each(
            function (Feed $feed) use ($setting, $value) {
                $this->info('Updating ' . $feed->name);
                $settings = $feed->settings;
                data_set($settings, $setting, $this->castValue($value));
                $feed->settings = $settings;
                $feed->save();
                $this->info('Updated ' . $feed->name);
            });
    }
    
    /**
     * @param $value
     *
     * @return bool
     */
    protected function castValue($value)
    {
        switch (strtolower($value)) {
            case "true":
                return true;
                break;
            case "false":
                return false;
                break;
            default:
                return $value;
        }
    }
    
}
