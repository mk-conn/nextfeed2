<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Http\Controllers;


use App\Models\Feed;
use Illuminate\Support\Facades\Auth;

class FeedsController extends Controller
{
    /**
     * @param $feedId
     */
    public function refresh($feedId)
    {
        $feeds = collect([]);

        if (!$feedId) {
            $feeds = Feed::all()
                         ->where('user_id', Auth::user()->id);
        } else {
            $feed = Feed::find($feedId);
            if ($feed) {
                $feeds->push($feed);
            }
        }

        $feeds->each(function (Feed $feed) {
            $feed->fetchNewArticles();
        });
    }

    /**
     * @param $feedId
     */
    public function cleanup($feedId)
    {

    }

    public function read($feedId)
    {

    }
}
