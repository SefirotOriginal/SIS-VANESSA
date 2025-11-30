<?php

return [
    // URL base del servidor remoto (ej. https://miapp.hostinger.com)
    'remote_url' => env('SYNC_REMOTE_URL', ''),

    // Token/clave que comparten el servidor y las instalaciones locales
    'remote_key' => env('SYNC_REMOTE_KEY', ''),

    // Tiempo en segundos para timeout HTTP
    'timeout' => env('SYNC_TIMEOUT', 10),
];
