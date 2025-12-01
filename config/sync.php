<?php

return [
    // URL base del servidor remoto (ej. https://miapp.hostinger.com)
    'remote_url' => env('SYNC_REMOTE_URL', ''),

    // Token/clave que comparten el servidor y las instalaciones locales
    'remote_key' => env('SYNC_REMOTE_KEY', ''),

    // Tiempo en segundos para timeout HTTP
    'timeout' => env('SYNC_TIMEOUT', 10),
    // Política de resolución de conflictos entre registros locales y remotos.
    // Opciones:
    //  - 'server_wins' : mantener la versión que ya existe en el servidor (ignorar incoming)
    //  - 'local_wins'  : aceptar siempre la versión entrante (sobrescribir)
    //  - 'latest_update': comparar 'updated_at' y quedarse con la más reciente
    //  - 'merge'       : merge superficial (campos no nulos del incoming reemplazan a los locales)
    'conflict_resolution' => env('SYNC_CONFLICT_RESOLUTION', 'server_wins'),
];
