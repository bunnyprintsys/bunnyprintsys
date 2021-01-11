<?php

use Illuminate\Database\Seeder;
use App\Models\MultiplierType;

class MultiplierTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MultiplierType::create([
            'name' => 'customer'
        ]);

        MultiplierType::create([
            'name' => 'agent'
        ]);
    }
}
