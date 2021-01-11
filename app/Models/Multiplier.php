<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Multiplier extends Model
{
    protected $fillable = [
        'value', 'multiplier_type_id'
    ];

    // relationships
    public function categoryable()
    {
        return $this->morphTo();
    }

    public function multiplierType()
    {
        return $this->belongsTo(MultiplierType::class);
    }

    public function customerMultiplierType()
    {
        return $this->multiplierType()->where('id', 1);
    }

    public function agentMultiplierType()
    {
        return $this->multiplierType()->where('id', 2);
    }

    // getter
    public function getValueAttribute($value)
    {
        return $value ? number_format($value/ 100, 2) : 0.00;
    }

    // setter
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = $value ? $value * 100 : 0;
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

        $sortable = ['id', 'value'];

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
