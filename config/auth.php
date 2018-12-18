<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard'     => 'supplier',
        'passwords' => 'supplier',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        // 供应商
        'supplier' => [
            'driver'   => 'jwt',
            'provider' => 'suppliers',
        ],

        'supplier_driver' => [
            'driver'   => 'jwt',
            'provider' => 'supplier_drivers',
        ],

        'supplier_salesman' => [
            'driver'   => 'jwt',
            'provider' => 'supplier_salesmen',
        ],
        // 客户
        'customer' => [
            'driver'   => 'jwt',
            'provider' => 'customers',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'suppliers' => [
            'driver' => 'eloquent',
            'model'  => \App\Models\Supplier\Supplier::class,
        ],
        'supplier_drivers' => [
            'driver' => 'eloquent',
            'model'  => \App\Models\Supplier\Driver::class,
        ],
        'supplier_salesmen' => [
            'driver' => 'eloquent',
            'model'  => \App\Models\Supplier\Salesman::class,
        ],
        'customers' => [
            'driver' => 'eloquent',
            'model'  => \App\Models\Customer\Customer::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'supplier' => [
            'provider' => 'suppliers',
            'table'    => 'password_resets',
            'expire'   => 60,
        ],
    ],
];
