<?php

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::create([
            'name' => 'Malaysia',
            'symbol' => 'MY',
            'code' => '60',
            'currency_name' => 'Malaysian Ringgit',
            'currency_symbol' => 'MYR'
        ]);

        Country::create([
            'name' => 'Singapore',
            'symbol' => 'SG',
            'code' => '65',
            'currency_name' => 'Singapore Dollar',
            'currency_symbol' => 'SGD'
        ]);
    }
}
