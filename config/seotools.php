<?php

return [
    'inertia' => env('SEO_TOOLS_INERTIA', false),
    'meta' => [
        'defaults' => [
            'title'        => 'DevHub',
            'titleBefore'  => false,
            'description'  => 'Tools, tutorials, and resources for modern developers.',
            'separator'    => ' — ',
            'keywords'     => ['developer tools', 'programming', 'web development', 'tutorials', 'code snippets'],
            'canonical'    => 'current',
            'robots'       => 'index, follow',
        ],
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],
        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        'defaults' => [
            'title'       => 'DevHub — Tools & Resources for Developers',
            'description' => 'Tools, tutorials, and resources for modern developers.',
            'url'         => null,
            'type'        => 'website',
            'site_name'   => 'DevHub',
            'images'      => [],
        ],
    ],
    'twitter' => [
        'defaults' => [
            'card' => 'summary_large_image',
            'site' => '@devhub',
        ],
    ],
    'json-ld' => [
        'defaults' => [
            'title'       => 'DevHub',
            'description' => 'Tools, tutorials, and resources for modern developers.',
            'url'         => 'current',
            'type'        => 'WebPage',
            'images'      => [],
        ],
    ],
];
