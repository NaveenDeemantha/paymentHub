<?php

return [
    'client_id_lkr' => env('PAYCENTER_CLIENT_ID_LKR'),
    'client_id_usd' => env('PAYCENTER_CLIENT_ID_USD'),
    'auth_token' => env('PAYCENTER_AUTH_TOKEN'),
    'endpoint_url' => env('PAYCENTER_ENDPOINT_URL', 'https://sampath.paycorp.lk/rest/service/proxy'),
    'hmac_secret' => env('PAYCENTER_HMAC_SECRET'),
];
