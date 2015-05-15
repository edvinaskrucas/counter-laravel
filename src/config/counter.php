<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Counter repository to store values.
    |--------------------------------------------------------------------------
    |
    | Counter service will resolve defined repository class.
    |
    */
    'repository' => 'Krucas\Counter\Integration\Laravel\DatabaseRepository',

    /*
    |--------------------------------------------------------------------------
    | Table name for DatabaseRepository.
    |--------------------------------------------------------------------------
    |
    | Database repository will use this table to store values.
    |
    */
    'table' => 'counter',

);
