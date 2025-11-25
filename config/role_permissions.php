<?php

return [
    // Map of role name => list of permission patterns.
    // Patterns support:
    // - '*' to match all permissions
    // - 'prefix.*' to match permissions that start with 'prefix.' (e.g. 'product.*')
    // - exact permission names (e.g. 'sales.create')
    'map' => [
        'SuperUsuario' => ['*'],

        // Administrador: acceso a usuarios, productos, roles y reportes
        'Administrador' => [
            'usuarios.*',
            'cashcuts.*',
            'category.*',
            'laboratory.*',
            'detailsale. *',
            'home.*',
            'presentation.*',
            'purchase.*',
            'reports.*',
            'sale.*',
            'product.*',
        ],

        // Cajero: venta y generación de recibos
        'Cajero' => [
            'reports.*',
            'sale.*',
            'product.*',
            'home.*',
            'cashcuts.*',
        ],

        // Inventario: gestión de stock, presentaciones, lotes
        'Inventario' => [
            'product.*',
            'batch.*',
            'presentations.*',
            'category.*',
            'detailsale.*',
            'laboratory.*',
        ],
    ],
];
