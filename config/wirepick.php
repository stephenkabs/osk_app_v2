<?php

return [
    // Wirepick Public RSA Key used for card encryption
    'public_key'  => env('WIREPICK_PUBLIC_KEY'),

    // Wirepick API Key from your .env
    'api_key'     => env('WIREPICK_API_KEY'),

    // Access token (the SAME token you use in Postman under Authorization → Bearer)
    'token'       => env('WIREPICK_DIRECT_TOKEN'),

    // UAT or PROD environment
    'environment' => env('WIREPICK_ENV', 'UAT'),

       // ADD THESE TWO 👇👇
    'client_id'     => env('WIREPICK_CLIENT_ID'),
    'client_secret' => env('WIREPICK_CLIENT_SECRET'),
];
