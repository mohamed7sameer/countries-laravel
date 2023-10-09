<?php

return [

    'cache' => [
        'enabled' => true,

        'service' => Mohamed7sameer\Countries\Package\Services\Cache\Service::class,

        'duration' => 180,

        'directory' => sys_get_temp_dir().'/__MOHAMED7SAMEER_COUNTRIES__/cache',
    ],

    'hydrate' => [
        'before' => true,

        'after' => true,

        'elements' => [
            'borders' => false,
            'cities' => false,
            'currencies' => false,
            'flag' => false,
            'geometry' => false,
            'states' => false,
            'taxes' => false,
            'timezones' => false,
            'timezones_times' => false,
            'topology' => false,
        ],
    ],

    'maps' => [
        'lca3' => 'cca3',
        'currencies' => 'currency',
    ],

    'routes' => [
        'enabled' => true,
    ],

    'validation' => [
        'enabled' => true,
        'rules' => [
            'country'           => 'name.common',
            'name'              => 'name.common',
            'nameCommon'        => 'name.common',
            'cca2',
            'cca3',
            'ccn3',
            'cioc',
            'currencies'        => 'ISO4217',
            'language_short'    => 'ISO639_3',
        ],
    ],

];
