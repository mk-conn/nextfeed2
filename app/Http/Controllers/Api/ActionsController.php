<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Feed;
use Illuminate\Http\Request;

class ActionsController extends Controller
{
    /**
     * @param Request $request
     * @param         $feedId
     * @param         $lastArticleId
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function markFeedRead(Request $request, $feedId, $lastArticleId)
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
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function searchArticles(Request $request)
    {
        $userId = $request->user()->id;
        $q = $request->get('q');

        $results = Article::search($q)
                          ->where('user_id', $userId)
                          ->get();

        return response()->json($results);
    }
}