<?php

use Illuminate\Database\Seeder;
use App\Models\ProductDelivery;

class AgentDeliveryMultiplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = ProductDelivery::where('product_id', 2)->where('delivery_id', 1)->first();
        $model->multipliers()->create(['value' => '0', 'multiplier_type_id' => 2]);
        $model = ProductDelivery::where('product_id', 2)->where('delivery_id', 2)->first();
        $model->multipliers()->create(['value' => '15', 'multiplier_type_id' => 2]);
        $model = ProductDelivery::where('product_id', 1)->where('delivery_id', 3)->first();
        $model->multipliers()->create(['value' => '30', 'multiplier_type_id' => 2]);
        $model = ProductDelivery::where('product_id', 1)->where('delivery_id', 4)->first();
        $model->multipliers()->create(['value' => '15', 'multiplier_type_id' => 2]);
        $model = ProductDelivery::where('product_id', 2)->where('delivery_id', 4)->first();
        $model->multipliers()->create(['value' => '75', 'multiplier_type_id' => 2]);
    }
}
