<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use ScoutEngines\Postgres\TsQuery\PhraseToTsQuery;

class ArticlesController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $userId = $request->user()->id;
        $q = $request->get('q');

        $results = Article::search($q, function ($builder, $config) use ($userId) {

            return new PhraseToTsQuery($builder->query, $config);
        })
                          ->where('user_id', $userId)
                          ->get();

        return response()->json($results);
    }
}