<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{

    protected $fillable = [
        'description', 'qty', 'price', 'amount',
        'product_id', 'transaction_id', 'created_by',
        'material_id'
    ];

// relationships
    public function material()
    {
        return $this->belongsTo(Material::class);
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
