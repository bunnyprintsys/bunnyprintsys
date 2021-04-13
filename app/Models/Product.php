<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Product extends Model
{
    protected $fillable = [
        'id', 'name', 'code', 'desc1', 'desc2',
        'is_material', 'is_shape', 'is_lamination', 'is_frame', 'is_finishing', 'is_delivery', 'is_quantity_multiplier', 'is_order_quantity'
    ];

    // relationships
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'product_materials', 'product_id', 'material_id');
    }

    public function shapes()
    {
        return $this->belongsToMany(Shape::class, 'product_shapes', 'product_id', 'shape_id');
    }

    public function laminations()
    {
        return $this->belongsToMany(Lamination::class, 'product_laminations', 'product_id', 'lamination_id');
    }

    public function frames()
    {
        return $this->belongsToMany(Frame::class, 'product_frames', 'product_id', 'frame_id');
    }

    public function finishings()
    {
        return $this->belongsToMany(Finishing::class, 'product_finishings', 'product_id', 'finishing_id');
    }

    public function deliveries()
    {
        return $this->belongsToMany(Delivery::class, 'product_deliveries', 'product_id', 'delivery_id');
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeId($query, $value)
    {
        $columnName = $this->getAliasColumnName('id');
        // dd($columnName);
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

    public function scopeCode($query, $value, $like = true)
    {
        $columnName = $this->getAliasColumnName('code');

        if ($like) {
            return $query->where($columnName, 'LIKE', '%'.$value.'%');
        }
        return $query->where($columnName, $value);
    }

    /**
     * @param $query
     * @param $input
     * @param bool $like
     * @param null $alias
     * @return mixed
     */
    public function scopeFilter($query, $input, $like = true, $alias = null)
    {
        if ($alias) {
            $this->alias = $alias;
        }

        if (Arr::get($input, 'id', false)) {
            $query->id($input['id']);
        }

        if (Arr::get($input, 'excluded_id', false)) {
            $query->whereNotIn('id', $input['excluded_id']);
        }

        if (Arr::get($input, 'name', false)) {
            $query->name($input['name'], $like);
        }

        if (Arr::get($input, 'code', false)) {
            $query->code($input['code'], $like);
        }

        return $query;
    }

    /**
     * @param $query
     * @param $input
     * @param null $alias
     * @return mixed
     */
    public function scopeSortBy($query, $input, $alias = null)
    {
        if ($alias) {
            $this->alias = $alias;
        }

        $sortable = ['name', 'product_code'];

        $inputKeys = array_keys($input);
        $notSupportedKeys = array_diff($inputKeys, $sortable);
        foreach ($notSupportedKeys as $key) {
            unset($input[$key]);
        }

        foreach ($input as $key => $value) {
            if (Arr::get($input, $key, false)) {
                $columnName = $key;
                $query->orderBy($columnName, $value);
            }
        }
        return $query;
    }

    /**
     * @param $columnName
     * @return string
     */
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
