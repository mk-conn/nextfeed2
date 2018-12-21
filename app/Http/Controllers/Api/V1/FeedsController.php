<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Models\Feed;
use Illuminate\Http\Request;
use Zend\Feed\Reader\Reader;

class FeedsController extends Controller
{
    /**
     * @param Request $request
     * @param         $feedId
     * @param         $lastArticleId
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function read(Request $request, $feedId, $lastArticleId)
    {
        /** @var Feed $feed */
        $feed = Feed::findOrFail($feedId);
        $this->authorize('update', $feed, $request);

        $success = $feed->read($lastArticleId);
        $result = [
            'success' => $success
        ];

        return response()->json($result);
    }

    /**
     * @param $url
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function discover($url)
    {
        $rssFeed = null;
        $feedLinks = Reader::findFeedLinks($url);
        $result = [];
        foreach ($feedLinks as $link) {
            $result[] = $link[ 'href' ];
        }

        return response()->json($result);
    }
}