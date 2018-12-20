<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Feed;
use App\Models\User;
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
}