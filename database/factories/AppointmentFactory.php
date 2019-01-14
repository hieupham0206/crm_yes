<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Appointment::class, function (Faker $faker) {
    return [
        'user_id'              => function () use ($faker) {
            return $faker->randomElement(\App\Models\User::get(['id'])->pluck('id')->toArray());
        },
        'lead_id'              => function () use ($faker) {
            return $faker->randomElement(\App\Models\Lead::get(['id'])->pluck('id')->toArray());
        },
        'appointment_datetime' => $faker->creditCardExpirationDate,
        'state'                => $faker->randomElement([-1, 1]),
        'is_queue'             => $faker->randomElement([-1, 1]),
        'is_show_up'           => $faker->randomElement([-1, 1]),
    ];
});
