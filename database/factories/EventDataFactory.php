<?php

use Faker\Generator as Faker;

$factory->define(App\Models\EventData::class, function (Faker $faker) {
    return [
        'code' => $faker->randomNumber(6),
        'appointment_id' => function() use($faker) {
            return $faker->randomElement(\App\Models\Appointment::get()->random()->pluck('id'));
        },
        'lead_id' => function() use($faker) {
            return $faker->randomElement(\App\Models\Lead::get()->random()->pluck('id'));
        },
        'state' => $faker->randomElement([1,2,3,4])
    ];
});
