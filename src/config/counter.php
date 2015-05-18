<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Counter repository driver.
    |--------------------------------------------------------------------------
    |
    | Counter service will resolve defined driver. Available drivers: array, database
    |
    */
    'driver' => 'database',

    /*
    |--------------------------------------------------------------------------
    | Configuration for database driver.
    |--------------------------------------------------------------------------
    |
    | Driver will use this configuration for init.
    |
    */
    'database' => array(

        'connection' => null,

        'table' => 'counter',

    ),

);
