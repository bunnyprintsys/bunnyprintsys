<?php

use Illuminate\Database\Seeder;
use App\Models\SalesChannel;

class SalesChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salesChannelArr = ['FB Pm', 'FB Pm (C)', 'Email', 'Whatsapp', 'Villa Whatsapp', 'Shopee'];

        foreach($salesChannelArr as $salesChannel) {
            SalesChannel::create([
                'name' => $salesChannel
            ]);
        }
    }
}
