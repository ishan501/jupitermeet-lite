<?php

return [
    'name' => 'JupiterMeet Lite',
    'manifest' => [
        'name' => 'JupiterMeet Lite',
        'short_name' => 'JupiterMeet Lite',
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation' => 'any',
        'status_bar' => 'black',
        'icons' => [
            '16x16' => [
                'path' => '/storage/images/favicon-16x16.png',
                'purpose' => 'any'
            ],
            '32x32' => [
                'path' => '/storage/images/favicon-32x32.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/storage/images/android-chrome-192x192.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/storage/images/android-chrome-512x512.pmg',
                'purpose' => 'any'
            ],
        ],
        'custom' => []
    ]
];
