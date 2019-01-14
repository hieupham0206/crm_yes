<?php

use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        factory(\App\Models\Appointment::class, 50)->create();

        factory(\App\Models\Appointment::class, 50)->create([
            'appointment_datetime' => now()->toDateTimeString(),
            'is_queue'             => 2,
        ]);
    }
}
