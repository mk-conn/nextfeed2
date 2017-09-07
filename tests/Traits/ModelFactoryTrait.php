<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace Tests\Traits;


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
     * @param Folder|null $folder
     * @param array       $attrs
     * @param int         $amount
     *
     * @return mixed
     */
    public function createFeed(Folder $folder = null, $attrs = [], $amount = 1)
    {
        if (!$folder) {
            $folder = $this->createFolder();
        }

        $feeds = factory(Feed::class, $amount)->make($attrs);

        if ($amount === 1) {
            $feed = $feeds->first();
            $folder->feeds()
                   ->save($feed);

            return $feed;
        }

        $folder->feeds()
               ->saveMany($feeds);

        return $feeds;
    }

    /**
     * @param User|null $user
     * @param array     $attrs
     * @param int       $amount
     *
     * @return mixed
     */
    public function createFolder(User $user = null, $attrs = [], $amount = 1)
    {
        if (!$user) {
            $user = $this->createUser();
        }

        $folders = factory(Folder::class, $amount)->make($attrs);

        if ($amount === 1) {
            $folder = $folders->first();
            $user->folders()
                 ->save($folder);

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
        $users = factory(User::class)->create($attrs);

        if ($amount = 1) {
            return $users->first();
        }

        return $users;
    }

}
