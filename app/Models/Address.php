<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Address extends Model
{
    protected $fillable = [
        'block', 'unit', 'building_name', 'road_name', 'postcode', 'area', 'name',
        'state_id', 'country_id', 'is_primary', 'typeable_id', 'typeable_type',
        'is_delivery', 'is_billing', 'is_active', 'contact',
        'alt_contact', 'slug_address'
    ];

    // relationships
    public function typeable()
    {
        return $this->morphTo();
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // setter
    public function setIsPrimaryAttribute($value)
    {
        $this->attributes['is_primary'] = $value ? $value : 0;
    }

    public function setIsBillingAttribute($value)
    {
        $this->attributes['is_billing'] = $value ? $value : 0;
    }

    public function setIsDeliveryAttribute($value)
    {
        $this->attributes['is_delivery'] = $value ? $value : 0;
    }

    // getter
    public function getFullAddressAttribute()
    {
        $full_address = '';
        if($this->unit) {
            $full_address .= $this->unit.', ';
        }
        if($this->block) {
            $full_address .= $this->block.', ';
        }
        if($this->building_name) {
            $full_address .= $this->building_name.', ';
        }
        if($this->road_name) {
            $full_address .= $this->road_name.', ';
        }
        if($this->postcode) {
            $full_address .= $this->postcode.' ';
        }
        if($this->area) {
            $full_address .= $this->area.', ';
        }
        if($this->state) {
            $full_address .= $this->state->name.', ';
        }
        if($this->country) {
            $full_address .= $this->country->name.'.';
        }

        return $full_address;
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeId($query, $value)
    {
        $columnName = $this->getAliasColumnName('id');

        if (is_array($value)) {
            return $query->whereIn($columnName, $value);
        }
        return $query->where($columnName, $value);
    }

    public function scopeIsPrimary($query, $value)
    {
        $columnName = $this->getAliasColumnName('is_primary');

        return $query->where($columnName, $value);
    }

    public function scopeIsBilling($query, $value)
    {
        $columnName = $this->getAliasColumnName('is_billing');

        return $query->where($columnName, $value);
    }

    public function scopeIsDelivery($query, $value)
    {
        $columnName = $this->getAliasColumnName('is_delivery');

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

        if (Arr::get($input, 'is_primary', false)) {
            $query->isPrimary($input['is_primary']);
        }

        if (Arr::get($input, 'is_billing', false)) {
            $query->isBilling($input['is_billing']);
        }

        if (Arr::get($input, 'is_delivery', false)) {
            $query->isDelivery($input['is_delivery']);
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

        $sortable = ['name'];

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
