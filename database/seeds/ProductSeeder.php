<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productArr = ["NC","Frosted NC","Flyer","Envelope","NCR CB","Tag","Money Packet","PostCard","Booklet","Car Decal","Alum RUS","Easel Stand","X Stand","Tripod Stand","Banner","Bunting","Synthetic Poster","Foamboard","Polycarbonate","Outdoor Sticker","Button Badge","Self Ink Stamp",];

        foreach($productArr as $product) {
            Product::create([
                'name' => $product
            ]);
        }
    }
}
