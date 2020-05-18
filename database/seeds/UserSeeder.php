<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::create([
            'join_date' => Carbon::today()->toDateString()
        ]);

        $admin->user()->create([
            'name' => 'brian',
            'email' => 'leehongjie91@gmail.com',
            'phone_number' => '123456780',
            'password' => '12345678'
        ]);
    }
}
