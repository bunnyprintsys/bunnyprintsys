<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{

    protected $fillable = [
        'description', 'qty', 'price', 'amount',
        'product_id', 'transaction_id', 'created_by',
        'material_id', 'shape_id', 'lamination_id', 'frame_id', 'finishing_id'
    ];

// relationships
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function shape()
    {
        return $this->belongsTo(Shape::class);
    }

    public function lamination()
    {
        return $this->belongsTo(Lamination::class);
    }

    public function frame()
    {
        return $this->belongsTo(Frame::class);
    }

    public function finishing()
    {
        return $this->belongsTo(Finishing::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
