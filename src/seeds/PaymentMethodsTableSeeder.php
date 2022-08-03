<?php

use Illuminate\Database\Seeder;
use Properos\Commerce\Models\PaymentMethod;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (count(PaymentMethod::all()) > 0) {
            \DB::table('payment_methods')->truncate();
        } 

      /*   DB::table('payment_methods')->insert([
            'name' => "paypal",
            'label' => "PayPal",
            'description' => 'An  electronic payment method using just the customer email address'
        ]); */

        \DB::table('payment_methods')->insert([
            'name' => "credit-card",
            'label' => "Credit/Debit Card",
            'description' => 'A payment method using a personal or commercial and valid credit or debit card',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \DB::table('payment_methods')->insert([
            'name' => "cash",
            'label' => "Cash",
            'description' => 'Payment in cash',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \DB::table('payment_methods')->insert([
            'name' => "cheque",
            'label' => "Cheque",
            'description' => 'Payment using a cheque',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \DB::table('payment_methods')->insert([
            'name' => "paypal",
            'label' => "Paypal",
            'description' => 'Payment using paypal',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
