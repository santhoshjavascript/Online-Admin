<?php

return [
    'paths' => ['api/*'], // Apply CORS to all API routes
    'allowed_methods' => ['*'], // Allow all HTTP methods
    'allowed_origins' => ['http://localhost:5173'], // Allow frontend origin
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Allow all headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // Set to true if you need cookies/auth headers
];