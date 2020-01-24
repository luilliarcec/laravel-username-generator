<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    /*
    |--------------------------------------------------------------------------
    | Type Case
    |--------------------------------------------------------------------------
    |
    | The final result of the username will depend on the case selected.
    | lower, upper or mixed
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
    'model' => '\App\User',

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
