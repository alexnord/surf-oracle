<?php

return [
    'tides' => 'https://tidesandcurrents.noaa.gov/api/datagetter?product=predictions&application=NOS.COOPS.TAC.WL&datum=MLLW&time_zone=GMT&units=english&interval=hilo&format=json',
    'weather' => [
        'azuremaps' => [
            'url' => 'atlas.microsoft.com',
            'clientId' => env('AZURE_MAPS_CLIENT_ID'),
            'subscriptionKey' => env('AZURE_MAPS_SUBSCRIPTION_KEY'),
        ]
    ],
    'surfline' => [
        'swell' => '',
    ],
];
