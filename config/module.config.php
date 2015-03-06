<?php

return [
    'view_helpers' => [
        'factories' => [
            'hangerSnippet' => 'HangerSnippet\Service\SnippetHelperServiceFactory'
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'hanger-snippet' => __DIR__ . '/../view',
        ],
    ],
    'hanger_snippet' => [
        'snippets' => [
            'google-nocaptcha-recaptcha' => [
                'placement' => 'google-nocaptcha-recaptcha',
            ],
        ],
    ],
];
