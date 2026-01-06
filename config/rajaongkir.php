<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RajaOngkir API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for RajaOngkir API integration
    | for shipping cost calculation based on distance between cities.
    |
    */

    'api_key' => env('RAJAONGKIR_API_KEY', 'cSz263cs82fb893c1a357f90EO9W5dpq'),
    
    'delivery_api_key' => env('RAJAONGKIR_DELIVERY_API_KEY', '9v5gi2zZ82fb893c1a357f90x1MTcnqw'),
    
    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),    // Origin city ID - Default to Jakarta (ID: 153)
    'origin_city_id' => env('RAJAONGKIR_ORIGIN_CITY_ID', 153),
    
    // Default weight in grams (500g)
    'default_weight' => 500,
    
    // Available couriers
    'couriers' => [
        'spx' => [
            'name' => 'SPX Express',
            'code' => 'spx'
        ],
        'jne' => [
            'name' => 'JNE',
            'code' => 'jne'
        ],
        'jnt' => [
            'name' => 'J&T Express',
            'code' => 'jnt'
        ],
        'idexpress' => [
            'name' => 'ID Express',
            'code' => 'idexpress'
        ],
        'sicepat' => [
            'name' => 'SiCepat',
            'code' => 'sicepat'
        ],
        'anteraja' => [
            'name' => 'AnterAja',
            'code' => 'anteraja'
        ],
        'lex' => [
            'name' => 'LEX ID',
            'code' => 'lex'
        ],
        'lionparcel' => [
            'name' => 'Lion Parcel',
            'code' => 'lionparcel'
        ],
        'ncs' => [
            'name' => 'NCS',
            'code' => 'ncs'
        ],
        'paxel' => [
            'name' => 'Paxel',
            'code' => 'paxel'
        ]
    ],
    
    // API endpoints
    'endpoints' => [
        'provinces' => '/province',
        'cities' => '/city',
        'cost' => '/cost'
    ]
];
