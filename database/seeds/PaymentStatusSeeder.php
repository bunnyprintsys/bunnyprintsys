<?php

use Illuminate\Database\Seeder;
use App\Models\PaymentStatus;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentStatus::create([
            'name' => 'Paid'
        ]);

        PaymentStatus::create([
            'name' => 'Owe'
        ]);
    }
}
