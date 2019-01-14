<?php

use Illuminate\Database\Seeder;

class EventDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\EventData::class, 50)->create();
    }
}
