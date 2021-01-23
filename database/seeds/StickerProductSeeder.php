<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class StickerProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'id' => 1,
            'name' => 'Label Sticker',
            'is_material' => true,
            'is_shape' => true,
            'is_lamination' => true
        ]);

        Product::create([
            'id' => 2,
            'name' => 'Inkjet Sticker',
            'is_material' => true,
            'is_shape' => true
        ]);
    }
}
