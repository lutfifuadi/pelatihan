<?php

return [
    'api_key' => env('WA_API_KEY', ''),
    'sender' => env('WA_SENDER', ''),
    'send_url' => env('WA_SEND_URL', 'https://wa.lutfifuadi.my.id/send-message'),
    'check_url' => env('WA_CHECK_URL', 'https://wa.lutfifuadi.my.id/check-number'),
    'timeout' => env('WA_TIMEOUT', 15),
    'retry' => [
        'max_attempts' => env('WA_RETRY_MAX', 3),
        'delay' => env('WA_RETRY_DELAY', 1000),
    ],
];
