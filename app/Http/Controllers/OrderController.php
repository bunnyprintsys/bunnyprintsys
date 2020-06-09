<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderQuantity;
use App\Models\ProductMaterial;
use App\Models\ProductShape;
use App\Models\ProductDelivery;
use App\Models\ProductLamination;
use App\Models\QuantityMultiplier;

class OrderController extends Controller
{
    // constructor
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'getLabelstickerQuotationApi'
        ]]);
    }

    // return order quotation index
    public function index()
    {
        return view('order.index');
    }

    // return label sticker api()
    public function getLabelstickerQuotationApi()
    {
        $product_id = 1;

        $this->validate(request(), [
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'material_id' => 'required',
            'orderquantity_id' => 'required',
            'shape_id' => 'required',
        ], [
            'material_id.required' => 'Please select a material',
            'orderquantity_id.required' => 'Please select the quantities',
            'shape_id.required' => 'Please select the shape'
        ]);

        $width = request('width');
        $height = request('height');
        $material_id = request('material_id')['id'];
        $orderquantity_id = request('orderquantity_id')['id'];
        $lamination_id = request('lamination_id')['id'];
        $shape_id = request('shape_id')['id'];
        $delivery_id = request('delivery_id')['id'];
        $dimension = [
            'width' => 0,
            'height' => 0
        ];
        $floor_width = 0;
        $floor_height = 0;
        $area = 0;
        $formula = 0;
        $total = 0;
        // dd($material_id, $shape_id, $lamination_id, $orderquantity_id, $delivery_id);

        $dimension = [
            'width' => 305,
            'height' => 455
        ];
        $this->validate(request(), [
            'width' => 'lte:305',
            'height' => 'lte:455'
        ]);

        $floor_width1 = floor($dimension['width'] / ($width + 3));
        $floor_height1 = floor($dimension['height']/ ($height + 3));

        $floor_width2 = floor($dimension['width']/ ($height + 3));
        $floor_height2 = floor($dimension['height']/ ($width + 3));

        $area1 = $floor_width1 * $floor_height1;
        $area2 = $floor_width2 * $floor_height2;

        if($area1 >= $area2){
            $area = $area1;
        }else {
            $area = $area2;
        }

        $orderquantity = OrderQuantity::findOrFail($orderquantity_id);
        $material = ProductMaterial::find($material_id);
        $shape = ProductShape::find($shape_id);
        $delivery = ProductDelivery::find($delivery_id);
        $lamination = ProductLamination::find($lamination_id);

        $formula = intval(round($orderquantity->qty/ $area) + 1);

        $quantitymultiplier = QuantityMultiplier::where('min', '<=', $formula)
            ->where('max', '>=', $formula)
            ->where('product_id', $product_id)
            ->first();

        $total = ($formula * $quantitymultiplier->multiplier * $material->multiplier * $shape->multiplier * ($lamination ? $lamination->multiplier : 1)) + $delivery->multiplier;

        return $total;
    }

    // return inkjet sticker index
    public function getInkjetstickerIndex()
    {
        return view('client.inkjetsticker');
    }

    // return inkjet sticker api()
    public function getInkjetstickerQuotationApi()
    {
        $product_id = 2;

        $this->validate(request(), [
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'material_id' => 'required',
            // 'orderquantity_id' => 'required',
            'shape_id' => 'required',
        ], [
            'material_id.required' => 'Please select a material',
            // 'orderquantity_id.required' => 'Please select the quantities',
            'shape_id.required' => 'Please select the shape'
        ]);

        $width = request('width');
        $height = request('height');
        $material_id = request('material_id');
        $orderquantity_id = request('orderquantity_id');
        $quantities = request('quantities');
        $shape_id = request('shape_id');
        $lamination_id = request('lamination_id');
        $finishing_id = request('finishing_id');
        $frame_id = request('frame_id');
        $delivery_id = request('delivery_id');
        $dimension = [
            'width' => 0,
            'height' => 0
        ];
        $cal_width = 0;
        $cal_height = 0;
        $area = 0;
        $formula = 0;
        $total = 0;

        $dimension = [
            'width' => 150,
            'height' => 500
        ];
/*
        $this->validate(request(), [
            'width' => 'lte:150',
            'height' => 'lte:500'
        ]); */

        $cal_width = $width * 0.035;
        $cal_height = $height * 0.035;

        $area = round($cal_width * $cal_height, 2);
/*
        $material = Productmaterial::where('material_id', $material_id->id)->where('product_id', $product_id)->first();
        $shape = Productshape::where('shape_id', $shape_id->id)->where('product_id', $product_id)->first();
        $lamination = Productlamination::where('lamination_id', $lamination_id->id)->where('product_id', $product_id)->first();
        $finishing = Productfinishing::where('finishing_id', $finishing_id->id)->where('product_id', $product_id)->first();
        $delivery = Productdelivery::where('delivery_id', $delivery_id->id)->where('product_id', $product_id)->first(); */
        // dd(request()->all());

        // $total = $shape->multiplier : 0 * ($material ? $material->multiplier : 0 + $lamination ? $lamination->multiplier : 0 + $finishing ? $finishing->multiplier : 0) * $area;

        $total = $shape->multiplier * ($material->multiplier + $lamination->multiplier + $finishing->multiplier);

        // $total = $total * $orderquantity->qty;
        $total = $total * $quantities;

        // dd($delivery);
        $total = $total + ($delivery ? $delivery->multiplier : 0);

        dd($total);

        return $total;

    }
}
