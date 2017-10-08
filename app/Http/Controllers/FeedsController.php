<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Http\Controllers;


use App\Models\Article;
use App\Models\Feed;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PicoFeed\Reader\Reader;

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
     * @param $url
     */
    public function discover($url)
    {
        if (!Auth::check()) {
            return response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $reader = new Reader;
        $resource = $reader->download($url);

        $feeds = $reader->find($resource->getUrl(), $resource->getContent());

        return response()->json($feeds);

    }

    /**
     * @param $feedId
     */
    public function cleanup($feedId, $days)
    {

    }

    /**
     * @param $feedId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function readAllArticles($feedId)
    {
        $user = Auth::user();
        $feed = Feed::with(['user'])
                    ->find($feedId);

        if ($feed->user->id === $user->id) {
            Article::where('feed_id', $feedId)
                   ->update(['read' => true]);
        }

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
