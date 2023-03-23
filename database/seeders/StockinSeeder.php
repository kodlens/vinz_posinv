<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StockinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $data = [
            [
                'item_id' => 4,
                'qty_in' => 10,
                'price' => 420,
                'srp' => 550,
                'stock_in_date' => '2023-03-23',
            ],
            

        ];

        \App\Models\StockIn::insertOrIgnore($data);

    }
}
