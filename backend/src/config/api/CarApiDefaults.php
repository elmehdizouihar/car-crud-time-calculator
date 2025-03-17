<?php
// src/Config/Api/CarApiConfig.php

namespace App\Config\Api;

class CarApiConfig
{
    public const CALCULATE_TRAVEL_TIME_DEFAULTS = [
        '_api_respond' => true,
        '_api_openapi_context' => [
            'summary' => 'Calculate travel time for a given distance',
            'description' => 'This endpoint calculates the time required for a car to travel a given distance based on its maximum speed.',
            'responses' => [
                '200' => [
                    'description' => 'Travel time calculated successfully',
                ],
                '400' => [
                    'description' => 'Invalid input',
                ],
                '404' => [
                    'description' => 'Car not found',
                ],
            ],
        ],
    ];
}