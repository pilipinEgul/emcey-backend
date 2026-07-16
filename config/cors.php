<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    // Real origins come from CORS_ALLOWED_ORIGINS (comma-separated) in .env.
    // The local dev origins below are always allowed so a Next.js dev server —
    // opened either as localhost or over the LAN IP (e.g. from a phone on the
    // same network) — can talk to this API without editing the server .env.
    'allowed_origins' => ['*'],
    // Any private-LAN IP on port 3000 (192.168.x.x / 10.x / 172.16-31.x), so the
    // dev server keeps working if your machine's local IP changes.
    'allowed_origins_patterns' => [
        '#^http://(?:192\.168\.\d{1,3}\.\d{1,3}|10\.\d{1,3}\.\d{1,3}\.\d{1,3}|172\.(?:1[6-9]|2[0-9]|3[01])\.\d{1,3}\.\d{1,3}):3000$#',
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
