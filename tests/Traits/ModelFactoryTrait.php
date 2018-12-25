<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace Tests\Traits;


use App\Models\Article;
use App\Models\Feed;
use App\Models\Folder;
use App\Models\User;

/**
 * Class ModelFactoryTrait
 *
 * @package Tests\Traits
 */
trait ModelFactoryTrait
{
    
    /**
     * @param User|null     $user
     * @param Folder|null   $folder
     * @param array         $attrs
     * @param int           $amount
     * @param callable|null $mock_function
     *
     * @return Feed|Feed[]
     */
    public function createFeed(
        User $user = null, Folder $folder = null, $attrs = [], $amount = 1, callable $mock_function = null
    ) {
        if (!$user) {
            $user = $this->createUser();
        }
        
        /** @var Feed[] $feeds */
        $feeds = factory(Feed::class, $amount)->make($attrs);
        
        $setRelations = function ($feed, $user, $folder) {
            $feed->user()
                 ->associate($user);
            if ($folder) {
                $feed->folder()
                     ->associate($folder);
            }
        };
        
        if ($amount === 1) {
            /** @var Feed $feed */
            $feed = $feeds->first();
            $setRelations($feed, $user, $folder);
            $feed->save();
            
            return $feed;
        }
        
        foreach ($feeds as $feed) {
            $setRelations($feed, $user, $folder);
            $feed->save();
        }
        
        return $feeds;
    }
    
    /**
     * @param User|null $user
     * @param array     $attrs
     * @param int       $amount
     *
     * @return Folder
     */
    public function createFolder(User $user = null, $attrs = [], $amount = 1)
    {
        if (!$user) {
            $user = $this->createUser();
        }
        
        $folders = factory(Folder::class, $amount)->make($attrs);
        
        if ($amount === 1) {
            $folder = $folders->first();
            $folder->user()
                   ->associate($user);
            $folder->save();
            
            return $folder;
        }
        
        $user->folders()
             ->saveMany($folders);
        
        return $folders;
    }
    
    /**
     * @param array $attrs
     * @param int   $amount
     *
     * @return mixed
     */
    public function createUser($attrs = [], $amount = 1)
    {
        $users = factory(User::class, $amount)->create($attrs);
        
        if ($amount === 1) {
            return $users->first();
        }
        
        return $users;
    }
    
    /**
     * @param Feed|null $feed
     * @param array     $attrs
     * @param int       $amount
     *
     * @return Article|Article[]
     */
    public function createArticle(Feed $feed = null, $attrs = [], $amount = 1)
    {
        $articles = factory(Article::class, $amount)->make($attrs);
        
        if (!$feed) {
            $feed = $this->createFeed();
        }
        
        if ($amount === 1) {
            $article = $articles->first();
            $article->feed()
                    ->associate($feed);
            $article->user()
                    ->associate($feed->user);
            $article->save();
            
            return $article;
        }
        
        $articles->each(
            function ($article) use ($feed) {
                $article->user()
                        ->associate($feed->user);
            });
        
        $feed->articles()
             ->saveMany($articles);
        
        return $articles;
    }
    
    
}
