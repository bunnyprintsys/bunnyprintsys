<?php

namespace App\Models;

use App\Traits\HasMultiplierType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Material extends Model
{
    use HasMultiplierType;

    protected $fillable = [
        'name'
    ];

    // relationships
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_materials', 'material_id', 'product_id');
    }

    public function productMaterials()
    {
        return $this->hasMany(ProductMaterial::class);
    }

    // scopes
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
        // dd($value, $query->get()->toArray());
        return $query;
    }

    public function scopeMultiplierType($query, $value)
    {
        // dd('dude123');
        $query = $query->whereHas('productMaterials', function($query) use ($value) {
            $query->type($value);
        });
        // dd('dude123');
        return $query;
    }

    public function scopeMultiplierUnbindType($query, $value)
    {
        $query = $query->whereHas('productMaterials', function($query) use ($value) {
            $query->unbindType($value);
        });
        // dd($query->get()->toArray());
        return $query;
    }

    public function scopeBindedProduct($query, $value = [])
    {
        return $query->whereHas('products', function($query) use ($value) {
            $query->whereIn('material_id', $value);
        });
    }

    public function scopeExcludeBindedProduct($query, $value = [])
    {
        $query->whereNotIn('id', $value);
    }

    public function scopeBindProduct($query, $value)
    {
        return $query->products()->create(['product_id', $value]);
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

        if (Arr::get($input, 'type', false)) {
            $query->multiplierType($input['type']);
        }

        if (Arr::get($input, 'unbind_type', false)) {
            $query->multiplierUnbindType($input['unbind_type']);
        }

        if(Arr::get($input, 'product_id', false)) {
            $query->productId($input['product_id']);
        }
        // dd('dudd', $query->get()->toArray());

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

        $sortable = ['id', 'name'];

        $inputKeys = array_keys($input);
        $notSupportedKeys = array_diff($inputKeys, $sortable);
        foreach ($notSupportedKeys as $key) {
            unset($input[$key]);
        }

        foreach ($input as $key => $value) {
            if (Arr::get($input, $key, false)) {
                $columnName = $key;
                // dd($columnName, $value);
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
