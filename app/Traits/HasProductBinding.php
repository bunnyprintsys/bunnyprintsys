<?php

namespace App\Traits;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

trait HasProductBinding
{
    // retrive list with binded, unbinded, binded multiplier, unbinded multiplier
    public function hasMultiplierBindings($model, $input)
    {
        $inputWithoutType = $input;
        $inputWithoutType['type'] = null;
        $bindedItems = $model->with(['multipliers', 'multipliers.multiplierType'])
                        ->filter($inputWithoutType)
                        ->orderBy('name')
                        ->get();

        $bindedItemsIdArr = [];
        foreach($bindedItems as $bindedItem) {
            array_push($bindedItemsIdArr, $bindedItem->id);
        }

        $unbindedItems = $model->with(['multipliers', 'multipliers.multiplierType'])
                            ->whereNotIn('id', $bindedItemsIdArr)
                            ->orderBy('name')
                            ->get();

        $bindedMultiplierItems = $model->with(['multipliers', 'multipliers.multiplierType'])
                        ->filter($input)
                        ->orderBy('name')
                        ->get();
                        // dd('sdd', $bindedMultiplierItems->toArray());

        $bindedMultiplierItemsIdArr = [];
        foreach($bindedMultiplierItems as $bindedMultiplierItem) {
            array_push($bindedMultiplierItemsIdArr, $bindedMultiplierItem->id);
        }
        // dd($bindedMultiplierMaterialsIdArr);
        $unbindedMultiplierItems = $model->with(['multipliers', 'multipliers.multiplierType'])
                                    ->filter($inputWithoutType)
                                    ->whereNotIn('id', $bindedMultiplierItemsIdArr)
                                    ->orderBy('name')
                                    ->get();

        return [
            'binded' => $bindedItems,
            'unbinded' => $unbindedItems,
            'bindedMultiplier' => $bindedMultiplierItems,
            'unbindedMultiplier' => $unbindedMultiplierItems
        ];
    }

    // retrive list with product binded, unbinded
    public function hasProductBindings($input, $model, $className)
    {
        $product = Product::findOrFail($input['product_id']);
        // dd('here', $className);
        $bindedItems = $product->$className;
        $bindedItemsId = $product->$className->pluck('id');
        $unBindedItems = $model->whereNotIn('id', $bindedItemsId)->get();

        return [
            'binded' => $bindedItems,
            'unbinded' => $unBindedItems
        ];
    }
}
