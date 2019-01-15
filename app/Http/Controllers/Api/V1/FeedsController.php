<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Models\Feed;
use App\Readers\FeedReader;
use Illuminate\Http\Request;

class FeedsController extends Controller
{
    /**
     * @param Request $request
     * @param         $feedId
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function read(Request $request, $feedId)
    {
        /** @var Feed $feed */
        $feed = Feed::findOrFail($feedId);
        $this->authorize('update', $feed, $request);

        $success = $feed->read();
        $result = [
            'success' => $success
        ];

        return response()->json($result);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function discover(Request $request)
    {
        $this->authorize('create', [Feed::class, $request]);
        $url = $request->get('url');

        $request->validate(
            [
                'url' => 'required|url'
            ]);

        $feedReader = app()->make(FeedReader::class);
        try {
            $feedLinks = $feedReader->discover($url);
        } catch (\Exception $e) {
            $code = $feedReader->getHttpClient()
                               ->getResponse()
                               ->getStatusCode();

            return response()->json(['errors' => ['message' => $e->getMessage()]], $code);
        }

        return response()->json(['links' => $feedLinks->getArrayCopy()]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $feedId
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function reloadIcon(Request $request, $feedId)
    {
        $feed = Feed::findOrFail($feedId);
        $this->authorize('update', [$request->user('api'), $feed]);

        if ($feed->fetchIcon()) {
            $feed->save();
        }
        $result = [
            'success' => true
        ];

        return response()->json($result);
    }

    /**
     * @param $feedId
     */
    public function cleanup($feedId, $days)
    {

    }
}