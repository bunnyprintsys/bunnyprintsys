<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Shape extends Model
{
    protected $fillable = [
        'name'
    ];

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

    public function scopeBindedProduct($query, $value = [])
    {
        return $query->whereHas('productShapes', function($query) use ($value) {
            $query->whereIn('shape_id', $value);
        });
    }

    public function scopeExcludeBindedProduct($query, $value = [])
    {
        return $query->whereHas('productShapes', function($query) use ($value) {
            $query->whereNotIn('shape_id', $value);
        });
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

        if (Arr::get($input, 'product_id', false)) {
            $query->whereHas('productShapes', function ($query) use ($input) {
                $query->whereHas('product', function($query) use ($input) {
                    return $query->filter([
                        'id' => $input['product_id']
                    ]);
                });
            });
        }
        // dd($input, $alias);
        if (Arr::get($input, 'product_shape_id', false)) {
            $query->whereHas('productShapes', function ($query) use ($input) {
                return $query->filter([
                    'id' => $input['product_shape_id']
                ]);
            });
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
