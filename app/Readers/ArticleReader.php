<?php


namespace App\Readers;


use Masterminds\HTML5;

/**
 * Class ArticleReader
 *
 * @package App\Readers
 */
class ArticleReader
{

    protected $cleanTags = [
        'names'      => ['head', 'script', 'svg', 'nav', 'style'],
        'attributes' => ['style', 'class'],
        'classes'    => ['social', 'share', 'facebook', 'twitter', 'google', 'flattr', 'share']
    ];

    /**
     * @var
     */
    protected $html;

    protected $errors = [];
    /**
     * @var \DOMDocument
     */
    protected $doc;

    /**
     * ArticleReader constructor.
     *
     * @param $html
     */
    public function __construct($html)
    {
        $this->html = $html;
    }

    /**
     *
     */
    public function getArticleContent()
    {
        $this->html = (new \tidy)->repairString(
            $this->html,
            [
                'drop-proprietary-attributes' => true,
                'fix-uri'                     => true,
                'wrap'                        => false,
                'output-html'                 => true,
                'quote-marks'                 => true,
                'merge-divs'                  => true,
                'merge-spans'                 => true,
                'quote-ampersand'             => true,
            ]
        );

        $content = $this->parseDom($this->html);

        if (!$content) {
            $content = $this->getBody();
        }

        return $content;
    }

    /**
     * @return string
     */
    protected function parseDom()
    {
        libxml_use_internal_errors(true);
        $html5 = new HTML5(
            [
                'encode_entities' => true,
                'disable_html_ns' => true
            ]);
        $this->doc = $html5->loadHTML($this->html);
        $this->clean();
        $this->doc->saveHTML();

        $article = $this->doc->getElementsByTagName('article');
        if ($article && $article->length) {
            $article = $article->item(0);

            return $this->doc->saveHTML($article);
        } else {
            $body = $this->doc->getElementsByTagName('body');
            if ($body && $body->length) {
                $article = $this->doc->createElement('article');
                // replace 'body' with 'article'
                $body = $body->item(0);
                $article->appendChild($body->cloneNode(true)); // todo: really replace

                return $this->doc->saveHTML($article);
            }
        }

        $this->errors = $html5->getErrors();

        if (empty($this->errors)) {
            $this->errors = libxml_get_errors();
            libxml_clear_errors();
        }
    }

    protected function clean()
    {
        $tags = $this->cleanTags;

        foreach ($tags[ 'names' ] as $tag) {
            $elements = $this->doc->documentElement->getElementsByTagName($tag);
            if ($elements && $elements->length) {
                $this->removeFromDom($elements);
            }
        }

        if (isset($tags[ 'classes' ])) {
            $query = '//*[';
            $contains = [];
            foreach ($tags[ 'classes' ] as $class) {
                $contains[] = sprintf('contains(@class, "%s")', $class);
            }
            $query .= implode(' or ', $contains) . ']';
            $xpath = new \DOMXPath($this->doc);
            $classElements = $xpath->query($query);
            if ($classElements && $classElements->length) {
                $this->removeFromDom($classElements);
            }
        }

        if (isset($tags[ 'attributes' ])) {
            $xpath = new \DOMXPath($this->doc);
            foreach ($tags[ 'attributes' ] as $attribute) {
                $attributeElements = $xpath->query(sprintf('//*[@%s]', $attribute));
                if ($attributeElements && $attributeElements->length) {
                    for ($i = 0; $i < $attributeElements->length; $i++) {
                        $attributeElements[ $i ]->removeAttribute($attribute);
                    }
                }
            }
        }
    }

    /**
     * @param \DOMNodeList $elements
     */
    protected function removeFromDom(\DOMNodeList $elements)
    {
        for ($i = 0; $i < $elements->length; $i++) {
            $element = $elements->item($i);
            $parent = $element->parentNode;
            $parent->removeChild($element);
        }
    }

    /**
     *
     */
    protected function getBody()
    {

    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    protected function findArticleByTag()
    {

    }

}