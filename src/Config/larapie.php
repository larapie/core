<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Path
    |--------------------------------------------------------------------------
    |
    | This value is the name of the file that will be used to save the bootstrap
    | cache to.
    |
    */

    'bootstrap_path' => env('BOOTSTRAP_PATH', '/cache/bootstrap.php'),

    /*
    |--------------------------------------------------------------------------
    | Modules
    |--------------------------------------------------------------------------
    |
    | These values represent the path & namespace to your modules folder of your application.
    |
    */

    'modules' => [
        'path'      => env('MODULES_PATH', 'app/Modules'),
        'namespace' => env('MODULES_NAMESPACE', '\\App\\Modules'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Foundation
    |--------------------------------------------------------------------------
    |
    | These values represent the path & namespace to your foundation folder of your application.
    |
    */

    'foundation' => [
        'path'      => env('FOUNDATION_PATH', 'app/Foundation'),
        'namespace' => env('FOUNDATION_NAMESPACE', '\\App\\Foundation'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Packages
    |--------------------------------------------------------------------------
    |
    | These values represent the path & namespace to your packages folder of your application.
    |
    */

    'packages' => [
        'path'      => env('PACKAGES_PATH', 'app/Packages'),
        'namespace' => env('PACKAGES_NAMESPACE', '\\App\\Packages'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    |
    | These values are the names and corresponding paths to certain larapie resources.
    |
    */

    'resources' => [
        'actions'       => '/Actions',
        'attributes'    => '/Attributes',
        'commands'      => '/Console',
        'configs'       => '/Config',
        'controllers'   => '/Http/Controllers',
        'contracts'     => '/Contracts',
        'events'        => '/Events',
        'factories'     => '/Database/Factories',
        'guards'        => '/Guards',
        'jobs'          => '/Jobs',
        'listeners'     => '/Listeners',
        'middleware'    => '/Http/Middleware',
        'migrations'    => '/Database/Migrations',
        'models'        => '/Models',
        'notifications' => '/Notifications',
        'observers'     => '/Observers',
        'policies'      => '/Policies',
        'permissions'   => '/Permissions',
        'providers'     => '/Providers',
        'requests'      => '/Http/Requests',
        'rules'         => '/Rules',
        'repositories'  => '/Repositories',
        'routes'        => '/Routes',
        'seeders'       => '/Database/Seeders',
        'services'      => '/Services',
        'transformers'  => '/Transformers',
        'tests'         => '/Tests',
        'resources'     => '/Resources',
    ],

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    |
    | The routing functionality for the application
    |
    */

    'routing' => [
        'provider' => \Larapie\Core\Providers\RouteServiceProvider::class,

        'groups' => [
            'web' => [
                'middleware' => ['web'],
                'domain'     => null,
                'prefix'     => null,
            ],
            'api' => [
                'middleware' => ['api'],
                'domain'     => null,
                'prefix'     => 'api',
            ],
            'api-noauth' => [
                'middleware' => ['api-noauth'],
                'domain'     => null,
                'prefix'     => 'api',
            ],
        ],
    ],
];
