<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use App\Traits\HasMultiplierType;

class QuantityMultiplier extends Model
{
    use HasMultiplierType;

    protected $fillable = [
        'min', 'max', 'product_id'
    ];

    // relationships
    public function product()
    {
      return $this->belongsTo(Product::class);
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

    public function scopeProductId($query, $value)
    {
        $columnName = $this->getAliasColumnName('product_id');
        if (is_array($value)) {
            return $query->whereIn($columnName, $value);
        }
        return $query->where($columnName, $value);
    }

    public function scopeMin($query, $value)
    {
        $columnName = $this->getAliasColumnName('min');

        return $query->where($columnName, '<=', $value);
    }

    public function scopeMax($query, $value)
    {
        $columnName = $this->getAliasColumnName('max');

        return $query->where($columnName, '>=', $value);
    }


    public function scopeMultiplierType($query, $value)
    {
        $query->type($value);
        return $query;
    }

    public function scopeMultiplierUnbindType($query, $value)
    {
        $query->unbindType($value);
        return $query;
    }

    public function scopeBindedProduct($query, $value = [])
    {
        return $query->whereHas('products');
    }

    public function scopeExcludeBindedProduct($query, $value = [])
    {
        $query->whereNotIn('id', $value);
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

        if (Arr::get($input, 'product_id', false)) {
            $query->productId($input['product_id']);
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

        if(Arr::get($input, 'min', false)) {
            $query->min($input['min']);
        }

        if(Arr::get($input, 'max', false)) {
            $query->max($input['max']);
        }

        return $query;
    }

    public function scopeSortBy($query, $input, $alias = null)
    {
        if ($alias) {
            $this->alias = $alias;
        }

        $sortable = ['id', 'min', 'max'];

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
