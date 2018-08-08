<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Available locales/languages
    |--------------------------------------------------------------------------
    |
    | Available locales for routing
    |
     */
    'locales'           => [
        'en' => [
            'name'             => 'English',
            'native_name'      => 'English',
            'flag'             => 'english-flag.png',
            'locale'           => 'en', // ISO 639-1
            'canonical_locale' => 'en_GB', // ISO 3166-1
            'full_locale'      => 'en_GB.UTF-8',
        ],'fr' => [
            'name'             => 'French',
            'native_name'      => 'French',
            'flag'             => 'french-flag.png',
            'locale'           => 'fr', // ISO 639-1
            'canonical_locale' => 'fr_GB', // ISO 3166-1
            'full_locale'      => 'fr_GB.UTF-8',
        ],'sp' => [
            'name'             => 'Spanish',
            'native_name'      => 'Spanish',
            'flag'             => 'spain-flag.png',
            'locale'           => 'sp', // ISO 639-1
            'canonical_locale' => 'sp_GB', // ISO 3166-1
            'full_locale'      => 'sp_GB.UTF-8',
        ],'hi' => [
            'name'             => 'Hindi',
            'native_name'      => 'Hindi',
            'flag'             => 'hindi-flag.png',
            'locale'           => 'hi', // ISO 639-1
            'canonical_locale' => 'hi_GB', // ISO 3166-1
            'full_locale'      => 'hi_GB.UTF-8',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback locale/language
    |--------------------------------------------------------------------------
    |
    | Fallback locale for routing
    |
     */
    'default_locale'    => 'en',

    /*
    |--------------------------------------------------------------------------
    | Set Carbon locale
    |--------------------------------------------------------------------------
    |
    | Call Carbon::setLocale($locale) and set current locale in middleware
    |
     */
    'set_carbon_locale' => true,

    /*
    |--------------------------------------------------------------------------
    | Set System locale
    |--------------------------------------------------------------------------
    |
    | Call setlocale(LC_ALL, $locale) and set current locale in middleware
    |
     */
    'set_system_locale' => true,

    /*
    |--------------------------------------------------------------------------
    | Exclude segments from redirect
    |--------------------------------------------------------------------------
    |
    | Exclude segments from redirects in the middleware
    |
     */
    'exclude_segments'  => [
        'admin',
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Basic route
    |
     */
    'text-route'        => [
        'route'      => 'texts',
        'controller' => '\Longman\LaravelMultiLang\Controllers\TextsController',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Cache parameters
    |
     */
    'cache'             => [
        'enabled'  => false,
        'store'    => env('CACHE_DRIVER', 'default'),
        'lifetime' => 1440,
    ],

    /*
    |--------------------------------------------------------------------------
    | DB Configuration
    |--------------------------------------------------------------------------
    |
    | DB parameters
    |
     */
    'db'                => [
        'autosave'    => true, // Autosave missing texts in database. Only when environment is local
        'connection'  => env('DB_CONNECTION', 'default'),
        'texts_table' => 'texts',
    ],

];
