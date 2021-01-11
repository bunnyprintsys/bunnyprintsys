<?php

use Illuminate\Database\Seeder;
use App\Models\Multiplier;
use App\Models\ProductMaterial;
use App\Models\ProductShape;
use App\Models\ProductLamination;
use App\Models\ProductFrame;
use App\Models\ProductFinishing;
use App\Models\ProductDelivery;
use App\Models\QuantityMultiplier;

class MultiplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = ProductMaterial::where('product_id', 1)->where('material_id', 1)->first();
        $model->multipliers()->create(['value' => '3']);
        $model = ProductMaterial::where('product_id', 1)->where('material_id', 2)->first();
        $model->multipliers()->create(['value' => '4.50']);
        $model = ProductMaterial::where('product_id', 1)->where('material_id', 3)->first();
        $model->multipliers()->create(['value' => '6']);
        $model = ProductMaterial::where('product_id', 1)->where('material_id', 4)->first();
        $model->multipliers()->create(['value' => '5.50']);
        $model = ProductMaterial::where('product_id', 1)->where('material_id', 5)->first();
        $model->multipliers()->create(['value' => '3']);
        $model = ProductMaterial::where('product_id', 2)->where('material_id', 6)->first();
        $model->multipliers()->create(['value' => '1.80']);
        $model = ProductMaterial::where('product_id', 2)->where('material_id', 7)->first();
        $model->multipliers()->create(['value' => '2']);
        $model = ProductMaterial::where('product_id', 2)->where('material_id', 8)->first();
        $model->multipliers()->create(['value' => '2.30']);
        $model = ProductMaterial::where('product_id', 2)->where('material_id', 9)->first();
        $model->multipliers()->create(['value' => '3']);
        $model = ProductMaterial::where('product_id', 2)->where('material_id', 10)->first();
        $model->multipliers()->create(['value' => '3']);

        $model = ProductShape::where('product_id', 1)->where('shape_id', 1)->first();
        $model->multipliers()->create(['value' => '1']);
        $model = ProductShape::where('product_id', 1)->where('shape_id', 2)->first();
        $model->multipliers()->create(['value' => '1']);
        $model = ProductShape::where('product_id', 1)->where('shape_id', 3)->first();
        $model->multipliers()->create(['value' => '1.20']);
        $model = ProductShape::where('product_id', 2)->where('shape_id', 1)->first();
        $model->multipliers()->create(['value' => '1']);
        $model = ProductShape::where('product_id', 2)->where('shape_id', 3)->first();
        $model->multipliers()->create(['value' => '2']);

        $model = ProductLamination::where('product_id', 1)->where('lamination_id', 1)->first();
        $model->multipliers()->create(['value' => '1.20']);
        $model = ProductLamination::where('product_id', 1)->where('lamination_id', 2)->first();
        $model->multipliers()->create(['value' => '1.20']);
        $model = ProductLamination::where('product_id', 26)->where('lamination_id', 3)->first();
        $model->multipliers()->create(['value' => '0']);

        $model = ProductDelivery::where('product_id', 2)->where('delivery_id', 1)->first();
        $model->multipliers()->create(['value' => '0']);
        $model = ProductDelivery::where('product_id', 2)->where('delivery_id', 2)->first();
        $model->multipliers()->create(['value' => '15']);
        $model = ProductDelivery::where('product_id', 1)->where('delivery_id', 3)->first();
        $model->multipliers()->create(['value' => '30']);
        $model = ProductDelivery::where('product_id', 1)->where('delivery_id', 4)->first();
        $model->multipliers()->create(['value' => '15']);
        $model = ProductDelivery::where('product_id', 2)->where('delivery_id', 4)->first();
        $model->multipliers()->create(['value' => '75']);

        $model = QuantityMultiplier::findOrFail(1);
        $model->multipliers()->create(['value' => '0.60']);
        $model = QuantityMultiplier::findOrFail(2);
        $model->multipliers()->create(['value' => '0.50']);
        $model = QuantityMultiplier::findOrFail(3);
        $model->multipliers()->create(['value' => '0.45']);
        $model = QuantityMultiplier::findOrFail(4);
        $model->multipliers()->create(['value' => '0.43']);
        $model = QuantityMultiplier::findOrFail(5);
        $model->multipliers()->create(['value' => '0.55']);
        $model = QuantityMultiplier::findOrFail(6);
        $model->multipliers()->create(['value' => '0.70']);
        $model = QuantityMultiplier::findOrFail(7);
        $model->multipliers()->create(['value' => '1']);
        $model = QuantityMultiplier::findOrFail(8);
        $model->multipliers()->create(['value' => '0.70']);
        $model = QuantityMultiplier::findOrFail(9);
        $model->multipliers()->create(['value' => '0.55']);
        $model = QuantityMultiplier::findOrFail(10);
        $model->multipliers()->create(['value' => '0.43']);
        $model = QuantityMultiplier::findOrFail(11);
        $model->multipliers()->create(['value' => '0.95']);
        $model = QuantityMultiplier::findOrFail(12);
        $model->multipliers()->create(['value' => '0.80']);
        $model = QuantityMultiplier::findOrFail(13);
        $model->multipliers()->create(['value' => '0.43']);
        $model = QuantityMultiplier::findOrFail(14);
        $model->multipliers()->create(['value' => '0.43']);
        $model = QuantityMultiplier::findOrFail(15);
        $model->multipliers()->create(['value' => '0.43']);
        $model = QuantityMultiplier::findOrFail(16);
        $model->multipliers()->create(['value' => '0.90']);
        $model = QuantityMultiplier::findOrFail(17);
        $model->multipliers()->create(['value' => '0.85']);
        $model = QuantityMultiplier::findOrFail(18);
        $model->multipliers()->create(['value' => '0.75']);
        $model = QuantityMultiplier::findOrFail(19);
        $model->multipliers()->create(['value' => '0.65']);
        $model = QuantityMultiplier::findOrFail(20);
        $model->multipliers()->create(['value' => '1']);
    }
}
