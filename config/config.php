<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Driver generator
    |--------------------------------------------------------------------------
    |
    | Generate user names by person names or by emails (name or email)
    |
    */

    'driver' => 'name',

    /*
    |--------------------------------------------------------------------------
    | Type Case
    |--------------------------------------------------------------------------
    |
    | The final result of the username will depend on the case selected. (lower or upper)
    |
    */

    'case' => 'lower',

    /*
    |--------------------------------------------------------------------------
    | Model Eloquent
    |--------------------------------------------------------------------------
    |
    | The model who will have a username, to avoid repeated.
    |
    */

    'model' => '\App\Models\User',

    /*
    |--------------------------------------------------------------------------
    | Column Model Database
    |--------------------------------------------------------------------------
    |
    | Name of the column where the username is stored.
    |
    */

    'column' => 'username'
];
