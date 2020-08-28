<?php

use Illuminate\Database\Seeder;
use App\Models\DeliveryMethod;

class DeliveryMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeliveryMethod::create([
            'name' => 'Self Collect'
        ]);

        DeliveryMethod::create([
            'name' => 'JNT'
        ]);

        DeliveryMethod::create([
            'name' => 'Motorex'
        ]);

        DeliveryMethod::create([
            'name' => 'Xend'
        ]);

        DeliveryMethod::create([
            'name' => 'Poslaju'
        ]);
    }
}
