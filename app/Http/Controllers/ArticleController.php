<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use PicoFeed\Config\Config;
use PicoFeed\Scraper\Scraper;

/**
 * Class OriginalArticleController
 *
 * @package App\Http\Controllers
 */
class ArticleController extends Controller
{
    public function scrapeContent(Request $request)
    {

        $url = $request->get('url');

        $config = new Config;

        $grabber = new Scraper($config);
        $grabber->setUrl($request->url);
        $grabber->execute();

// Get raw HTML content
        $raw = $grabber->getRawContent();

// Get relevant content
        $relevant = $grabber->getRelevantContent();

// Get filtered relevant content
        $filtered = $grabber->getFilteredContent();

// Return true if there is relevant content
        return $relevant;
    }
}
