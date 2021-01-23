<?php

namespace App\Models;

use App\Traits\HasMultiplierType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Shape extends Model
{
    use HasMultiplierType;

    protected $fillable = [
        'name'
    ];

    // relationships
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_shapes', 'shape_id', 'product_id');
    }

    public function productShapes()
    {
        return $this->hasMany(ProductShape::class);
    }

    // scope
    public function scopeId($query, $value)
    {
        $columnName = $this->getAliasColumnName('id');
        if (is_array($value)) {
            return $query->whereIn($columnName, $value);
        }
        return $query->where($columnName, $value);
    }

    public function scopeName($query, $value, $like = true)
    {
        $columnName = $this->getAliasColumnName('name');

        if ($like) {
            return $query->where($columnName, 'LIKE', '%'.$value.'%');
        }
        return $query->where($columnName, $value);
    }

    public function scopeProductId($query, $value)
    {
        $query = $query->whereHas('products', function($query) use ($value) {
            $query->where('products.id', $value);
        });

        return $query;
    }

    public function scopeMultiplierType($query, $value)
    {
        return $query->whereHas('productShapes', function($query) use ($value) {
            $query->type($value);
        });
    }

    public function scopeMultiplierUnbindType($query, $value)
    {
        $query = $query->whereHas('productShapes', function($query) use ($value) {
            $query->unbindType($value);
        });

        return $query;
    }

    public function scopeBindedProduct($query, $value = [])
    {
        return $query->whereHas('products', function($query) use ($value) {
            $query->whereIn('shape_id', $value);
        });
    }

    public function scopeExcludeBindedProduct($query, $value = [])
    {
        return $query->whereNotIn('id', $value);

    }

    public function scopeBindProduct($query, $value)
    {
        return $query->products()->create(['product_id', $value]);
    }

    // filter
    public function scopeFilter($query, $input, $alias = null, $like = true)
    {
        if ($alias) {
            $this->alias = $alias;
        }

        if (Arr::get($input, 'id', false)) {
            $query->id($input['id']);
        }

        if (Arr::get($input, 'name', false)) {
            $query->name($input['name'], $like);
        }

        if (Arr::get($input, 'type', false)) {
            $query->multiplierType($input['type']);
        }

        if (Arr::get($input, 'unbind_type', false)) {
            $query->multiplierUnbindType($input['unbind_type']);
        }

        if(Arr::get($input, 'product_id', false)) {
            $query->productId($input['product_id']);
        }

        return $query;
    }

    public function scopeSortBy($query, $input, $alias = null)
    {
        if ($alias) {
            $this->alias = $alias;
        }

        $sortable = ['id', 'name'];

        $inputKeys = array_keys($input);
        $notSupportedKeys = array_diff($inputKeys, $sortable);
        foreach ($notSupportedKeys as $key) {
            unset($input[$key]);
        }

        foreach ($input as $key => $value) {
            if (Arr::get($input, $key, false)) {
                $columnName = $this->getAliasColumnName($key);
                $query->orderBy($columnName, $value);
            }
        }
        return $query;
    }

    protected function getAliasColumnName($columnName)
    {
        if (strpos($columnName, '.') !== false) {
            return $columnName;
        }

        if ($this->alias) {
            $columnName = $this->alias . '.' . $columnName;
        }
        return $columnName;
    }
}
