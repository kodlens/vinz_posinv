<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ItemSeeder extends Seeder
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
                'barcode' => '',
                'serial' => '',
                'brand' => 'KINGSTON',
                'model' => '',
                'item_name' => 'LAPTOP RAM DDR4 2400Mhz KINGSTON',
                'active' => 1
            ],
            [
                'barcode' => '',
                'serial' => '',
                'brand' => 'KINGSTON',
                'model' => '',
                'item_name' => '240 SSD KINGSTON FOR LAPTOP',
                'active' => 1
            ],
            [
                'barcode' => '',
                'serial' => '',
                'brand' => 'KINGSTON',
                'model' => '',
                'item_name' => '512 SSD KINGSTON FOR LAPTOP',
                'active' => 1
            ],
            [
                'barcode' => '',
                'serial' => '',
                'brand' => 'A4TECH',
                'model' => '',
                'item_name' => 'MOUSE AND KEYBOARD USB TYPE A4TECH',
                'active' => 1
            ],

        ];

        \App\Models\Item::insertOrIgnore($data);
    }
}
