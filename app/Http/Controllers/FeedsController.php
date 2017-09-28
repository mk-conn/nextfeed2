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
     * @param $id
     */
    public function refresh($id)
    {
        $feeds = collect([]);

        if (!$id) {
            $feeds = Feed::all()
                         ->where('user_id', Auth::user()->id);
        } else {
            $feed = Feed::find($id);
            if ($feed) {
                $feeds->push($feed);
            }
        }

        $feeds->each(function (Feed $feed) {
            $feed->fetchNewArticles();
        });
    }
}
