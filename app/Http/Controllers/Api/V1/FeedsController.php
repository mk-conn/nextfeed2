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
}