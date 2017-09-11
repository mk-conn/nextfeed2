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
        $baseUrl = request()->getSchemeAndHttpHost();

        $indexHtml = file_get_contents(public_path('ember-frontend/index.html'));

        $pattern = [
            '~assets/vendor.css~',
            '~assets/ember-larafum.css~',
            '~assets/vendor.js~',
            '~assets/ember-larafum.js~',
            '~/ember-cli-live-reload.js~'
        ];

        $replacements = [
            'ember-frontend/assets/vendor.css',
            'ember-frontend/assets/ember-larafum.css',
            'ember-frontend/assets/vendor.js',
            'ember-frontend/assets/ember-larafum.js',
            "http://127.0.0.1:4200/ember-cli-live-reload.js"
        ];

        $indexHtml = preg_replace_array($pattern, $replacements, $indexHtml);


        return view('app', ['app' => $indexHtml]);

    }
}
