<?php

namespace App\Models;


use Illuminate\Support\Facades\Auth;

/**
 * Class ArticleAction
 *
 * @package App\Models
 */
class ArticleAction extends BaseAction
{
    /**
     * @return $this
     */
    public function search()
    {
        $results = Article::search($this->params['q'])
                          ->where('user_id', Auth::user()
                                                 ->getAuthIdentifier())
                          ->get();
        $this->result['articles'] = $results;

        return $this;
    }
}
