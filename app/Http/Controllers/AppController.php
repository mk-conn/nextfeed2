<?php
/**
 * -- file description --
 *
 * @author Marko Krüger <plant2code@marko-krueger.de>
 *
 */

namespace App\Http\Controllers;


class AppController
{
    public function index()
    {

        $indexHtml = file_get_contents(public_path('frontend/index.html'));

        $pattern = [
            '~/(assets/.*\.(css|js))~',
            '~/ember-cli-live-reload.js~'
        ];

        $replacements = [
            '/frontend/$1',
            "http://127.0.0.1:4200/ember-cli-live-reload.js"
        ];

        $indexHtml = preg_replace($pattern, $replacements, $indexHtml);


        return view('app', ['app' => $indexHtml]);
    }
}
