<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Api Domain
    |--------------------------------------------------------------------------
    |
    | This value is the domain host name that will be used to register the routes in the api group.
    | Set this value to null if you want to allow access from multiple domains.
    |
    */

    'api_url' => env('API_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Api Subdomain
    |--------------------------------------------------------------------------
    |
    | This value is the subdomain that will be used to register the routes in the api group.
    | Setting this value to null will result in using a 'api' prefix instead of a subdomain.
    |
    */

    'api_subdomain' => env('API_SUBDOMAIN', 'api'),

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
    | Providers
    |--------------------------------------------------------------------------
    |
    | The providers for certain larapie base functionality.
    |
    */

    'providers' => [
        'routing' => \App\Foundation\Providers\RouteServiceProvider::class,
    ],
];
