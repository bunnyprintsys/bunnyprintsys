<?php

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'name' => 'Pending Artwork'
        ]);

        Status::create([
            'name' => 'Pending Payment'
        ]);

        Status::create([
            'name' => 'Production'
        ]);

        Status::create([
            'name' => 'Delivered'
        ]);

        Status::create([
            'name' => 'Void'
        ]);
    }
}
