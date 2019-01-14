<?php

use Illuminate\Database\Seeder;

class TeleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //seed 60 telco
        $i = 1;
        while ($i < 61) {
            $userData = [
                'name'           => "telco{$i}",
                'username'       => "telehcm{$i}",
                'password'       => \Hash::make('1234@Abcd'),
                'remember_token' => str_random(10),
            ];

            $user = \App\Models\User::create($userData);

            $user->assignRole([6]);
            $i++;
        }
    }
}
