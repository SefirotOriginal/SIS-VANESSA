<?php

return [
    // Jerarquía de roles: mayor número = mayor prioridad/permiso
    // Ajusta los nombres para que coincidan con los que usas en la base de datos/seeder
    'levels' => [
        'SuperUsuario' => 100,
        'Administrador' => 50,
        'Cajero' => 10,
        'Inventario' => 10,
    ],
];
