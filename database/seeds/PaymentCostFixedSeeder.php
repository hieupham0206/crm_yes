<?php

use Illuminate\Database\Seeder;

class PaymentCostFixedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            [
                'name'           => 'HolidayPlus',
                'cost'           => 3000000,
                'payment_method' => 5,
            ],
            [
                'name'           => 'YesAdmin',
                'cost'           => 7000000,
                'payment_method' => 5,
            ],
            [
                'name'           => 'RCI',
                'cost'           => 7000000,
                'payment_method' => 5,
            ],
            [
                'name'           => 'Phí phụ thu',
                'cost'           => 0,
                'payment_method' => 5,
            ],
        ];

        \App\Models\PaymentCost::insert($datas);
    }
}
