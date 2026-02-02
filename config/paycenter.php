<?php

return [
    'client_id' => env('PAYCENTER_CLIENT_ID'),
    'auth_token' => env('PAYCENTER_AUTH_TOKEN'),
    'endpoint_url' => env('PAYCENTER_ENDPOINT_URL', 'https://api.paycenter.com/v4'), // Placeholder default
    'hmac_secret' => env('PAYCENTER_HMAC_SECRET'),
];
