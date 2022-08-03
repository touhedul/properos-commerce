<?php

use Illuminate\Database\Seeder;
use Properos\Commerce\Models\ShippingMethod;

class ShippingMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (count(ShippingMethod::all()) > 0) {
            \DB::table('shipping_methods')->truncate();
        }

        \DB::table('shipping_methods')->insert([
            'name' => "manual",
            'label' => "Manual",
            'description' => '',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \DB::table('shipping_methods')->insert([
            'name' => "ups",
            'label' => "UPS",
            'description' => '',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \DB::table('shipping_methods')->insert([
            'name' => "dhl",
            'label' => "DHL",
            'description' => '',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \DB::table('shipping_methods')->insert([
            'name' => "usps",
            'label' => "USPS",
            'description' => '',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \DB::table('shipping_methods')->insert([
            'name' => "fedex",
            'label' => "FEDEX",
            'description' => '',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
