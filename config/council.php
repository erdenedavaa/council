<?php

return [
    'recaptcha' => [
        'key' => env('RECAPTCHA_KEY'),
        'secret' => env('RECAPTCHA_SECRET')
    ],

    'administrators' => [
        // Add the email address of users who should be administrators here.
        // 'o@o.com'
    ]
];
