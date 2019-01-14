<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProvinceSeeder::class,

            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,

            ReasonBreakSeeder::class,
            LeadWithStateMemberSeeder::class,
            TeleSeeder::class
        ]);

        if (App::environment() === 'local') {
            // Chạy seeder ở môi trường local
            $this->call([
                LeadSeeder::class,
                HistoryCallSeeder::class,
                AppointmentSeeder::class,
                CallbackSeeder::class,
            ]);
        }
    }
}
