<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\ArticleAction;
use App\Models\Feed;
use App\Models\FeedAction;
use App\Models\Folder;
use App\Models\Setting;
use App\Models\User;
use App\Policies\ArticleActionPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\FeedActionPolicy;
use App\Policies\FeedPolicy;
use App\Policies\FolderPolicy;
use App\Policies\SettingPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Article::class       => ArticlePolicy::class,
        Feed::class          => FeedPolicy::class,
        Folder::class        => FolderPolicy::class,
        Setting::class       => SettingPolicy::class,
        User::class          => UserPolicy::class,
        FeedAction::class    => FeedActionPolicy::class,
        ArticleAction::class => ArticleActionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        //
    }
}
