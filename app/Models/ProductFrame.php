<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use App\Traits\HasMultiplierType;

class ProductFrame extends Model
{
    use HasMultiplierType;

    protected $fillable = [
        'product_id', 'frame_id', 'multiplier'
    ];

    // relationships
    public function product()
    {
      return $this->belongsTo(Product::class);
    }

    public function frame()
    {
        return $this->belongsTo(Frame::class);
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

    public function scopeFrameId($query, $value)
    {
        $columnName = $this->getAliasColumnName('frame_id');
        if (is_array($value)) {
            return $query->whereIn($columnName, $value);
        }
        return $query->where($columnName, $value);
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

        if (Arr::get($input, 'frame_id', false)) {
            $query->frameId($input['frame_id']);
        }

        return $query;
    }

    public function scopeSortBy($query, $input, $alias = null)
    {
        if ($alias) {
            $this->alias = $alias;
        }

        $sortable = ['id', 'name', 'frames.name'];

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
