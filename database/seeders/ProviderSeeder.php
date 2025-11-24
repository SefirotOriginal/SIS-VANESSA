<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Provider;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        Provider::create([
            'referenceNumber' => 5001,
            'companyName'     => 'Fármacos de Occidente',
            'email'           => 'contacto@farmocc.com',
            'phoneNumber'     => 12345678,
            'address'         => 'Zona Industrial Belenes',
            'city'            => 'Zapopan',
            'state'           => 'Jalisco',
            'country'         => 'México',
            'zipCode'         => '45100'
        ]);

        Provider::create([
            'referenceNumber' => 5002,
            'companyName'     => 'Proveedor Global Med',
            'email'           => 'ventas@globalmed.com',
            'phoneNumber'     => 87654321,
            'address'         => 'Av. Vallarta 3000',
            'city'            => 'Guadalajara',
            'state'           => 'Jalisco',
            'country'         => 'México',
            'zipCode'         => '44500'
        ]);
    }
}