<?php
return [
    'feed' => [
        'articles' => [
            'keep'    => env('FEED_ARTICLES_KEEP', 10),
            'cleanup' => [
                'keepUnread' => env('FEED_ARTICLES_CLEANUP_KEEPUNREAD', false)
            ]
        ]
    ]
];