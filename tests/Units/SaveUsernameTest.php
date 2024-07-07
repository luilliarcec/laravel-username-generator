<?php

namespace Tests\Units;

use Tests\Models\Customer;
use Tests\Models\User;

it('save the generated username', function () {
    $user = User::factory()
        ->create([
            'name' => 'Luis Andrés Arce Cárdenas',
        ]);

    expect($user->username)
        ->toBe('larcec');
});

it('adds a suffix to the username if a duplicate exists', function ($existing, $expected) {
    if (is_array($existing)) {
        $existing = array_map(fn ($username) => ['username' => $username], $existing);
    } else {
        $existing = [['username' => $existing]];
    }

    User::factory()->createManyQuietly($existing);

    $user = User::factory()
        ->create([
            'name' => 'Luis Andrés Arce Cárdenas',
        ]);

    expect($user->username)
        ->toBe($expected);
})->with([
    'no matches' => ['larcecos', 'larcec'],
    'with duplicate' => ['larcec', 'larcec1'],
    'with duplicate with suffix' => ['larcec18', 'larcec19'],
    'with similarities' => [
        [
            'larcecos',
            'larcec985',
            'larcec45',
            'larcec1445',
            'larcec2045',
            'larcecaz',
            'larceca75',
            'larcecaz85',
        ],
        'larcec2046'
    ],
]);

it('stores the generated username by first and last name separately by transforming it uppercase', function () {
    $customer = Customer::factory()
        ->create([
            'first_name' => 'Luis Andrés',
            'last_name' => 'Arce Cárdenas',
        ]);

    expect($customer->username)
        ->toBe('LARCEC');
});
