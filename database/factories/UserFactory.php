<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\Foundry\System\Models\User::class, function (Faker $faker) {
    return [
        'display_name' => $faker->name,
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'active' => 1,
        'super_admin' => false,
        'password' => 'test1234'
    ];
});

$factory->state(\Foundry\System\Models\User::class, 'super_admin', [
    'super_admin' => true
]);
