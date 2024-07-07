<?php

namespace Tests\Units;

use Illuminate\Support\Benchmark;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

it('performs a performance test on the query of similar users', function () {
    ini_set('memory_limit', '-1');

    $users = User::factory()->count(100_000)->make();

    $users
        ->chunk(500)
        ->each(function (Collection $chunk) {
            $records = $chunk
                ->map(fn (User $user) => $user->toArray())
                ->toArray();

            DB::table('users')->insert($records);
        });

    $user = User::factory()->make(['name' => 'Luis Andrés Arce Cárdenas']);

    Benchmark::dd(fn () => $user->getUsername(), 10);

    // MySQL: 0.851ms
    // SQLServer: 7.739ms
    // PostgresSQL: 7.739ms
})->skip();
