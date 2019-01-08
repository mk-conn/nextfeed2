<?php


namespace App\ViewComposers;


use Illuminate\Support\Facades\App;
use Illuminate\View\View;

class AppComposer
{

    public function compose(View $view)
    {
        $locale = App::getLocale();
        $indexHtml = file_get_contents(public_path('frontend/index.html'));
        $pattern = [
            '~/(assets/.*\.(css|js))~',
            '~/ember-cli-live-reload.js~'
        ];
        $replacements = [
            '/frontend/$1',
            null
            //            "http://127.0.0.1:4200/ember-cli-live-reload.js"
        ];
        $indexHtml = preg_replace($pattern, $replacements, $indexHtml);

        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->loadHTML($indexHtml);

        $head = $doc->getElementsByTagName('head')
                    ->item(0);
        $csrf = $doc->createElement('meta');
        $csrf->setAttribute('name', 'csrf-token');
        $csrf->setAttribute('content', csrf_token());
        $head->appendChild($csrf);

        $notify = $doc->createElement('meta');
        $notify->setAttribute('name', 'notification');
        $notify->setAttribute('content', 0);
        $head->appendChild($notify);

        $apiClient = $doc->createElement('meta');
        $apiClient->setAttribute('name', 'api-client-id');
        $apiClient->setAttribute('content', env('PASSPORT_CLIENT_ID'));
        $head->appendChild($apiClient);

        if (app()->environment() === 'local') {
            $liveReload = $doc->createElement('script');
            $liveReload->setAttribute('src', 'http://127.0.0.1:4200/_lr/livereload.js?port=4200&host=127.0.0.1');
            $liveReload->setAttribute('type', 'text/javascript');
            $head->appendChild($liveReload);
        }

        $body = $doc->getElementsByTagName('body')
                    ->item(0);

        $docStart = $doc->saveHTML($head);
        $docContent = $doc->saveHTML($body);
        $docEnd = '';

        $view->with('lang', $locale);
        $view->with('docStart', $docStart);
        $view->with('docContent', $docContent);
        $view->with('docEnd', $docEnd);
    }

}