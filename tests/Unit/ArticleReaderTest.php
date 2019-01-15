<?php


namespace Tests\Unit;


use App\Readers\ArticleReader;
use Tests\TestCase;

class ArticleReaderTest extends TestCase
{
    public function testParseDom()
    {
//        $html = file_get_contents(base_path('tests/data/article01.html'));
//        $articleReader = new ArticleReader($html);
//        $content = $articleReader->getArticleContent();

        $html = file_get_contents(base_path('tests/data/article02.html'));
        $articleReader = new ArticleReader($html);
        $content = $articleReader->getArticleContent();

    }


}