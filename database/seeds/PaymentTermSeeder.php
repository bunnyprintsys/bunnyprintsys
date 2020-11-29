<?php

use Illuminate\Database\Seeder;
use App\Models\PaymentTerm;

class PaymentTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentTerm::create([
            'name' => 'Bank Transfer'
        ]);

        PaymentTerm::create([
            'name' => 'COD'
        ]);

        PaymentTerm::create([
            'name' => 'Cheque'
        ]);

        PaymentTerm::create([
            'name' => 'Credit Term'
        ]);
    }
}
