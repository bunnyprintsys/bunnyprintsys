<?php

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSecondSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::truncate();

        Status::create([
            'id' => 1,
            'name' => 'New'
        ]);

        Status::create([
            'id' => 2,
            'name' => 'In Production'
        ]);

        Status::create([
            'id' => 3,
            'name' => 'Done Production'
        ]);

        Status::create([
            'id' => 4,
            'name' => 'Delivering'
        ]);

        Status::create([
            'id' => 5,
            'name' => 'Completed'
        ]);

        Status::create([
            'id' => 99,
            'name' => 'Void'
        ]);
    }
}
