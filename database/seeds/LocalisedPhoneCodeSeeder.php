<?php

use Illuminate\Database\Seeder;
use App\Models\Country;

class LocalisedPhoneCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country = Country::findOrFail(1);
        $country->update([
            'localised_phone_code' => 0
        ]);
    }
}
