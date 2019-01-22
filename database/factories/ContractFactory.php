<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Contract::class, function (Faker $faker) {
    return [
        'contract_no' => 'HN(HYV6788678)',
    ];
});
