<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Voucher extends Model
{
    protected $fillable = [
        'name', 'desc', 'valid_from', 'valid_to', 'created_by', 'updated_by',
        'is_percentage', 'is_unique_customer', 'is_count_limit', 'value',
        'count_limit', 'is_active'
    ];

    // relationships
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    // custom methods
    public function isActive()
    {
        return $this->status === 1;
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

    public function scopeName($query, $value, $like = true)
    {
        $columnName = $this->getAliasColumnName('name');

        if($like) {
            return $query->where($columnName, 'LIKE', '%'.$value.'%');
        }
        return $query->where($columnName, $value);
    }

    public function scopeValidFrom($query, $value)
    {
        $columnName = $this->getAliasColumnName('valid_from');

        return $query->whereDate($columnName, '=', $value);
    }

    public function scopeValidTo($query, $value)
    {
        $columnName = $this->getAliasColumnName('valid_to');

        return $query->whereDate($columnName, '=', $value);
    }

    public function scopeStatus($query, $value)
    {
        $columnName = $this->getAliasColumnName('status');

        return $query->where($columnName, '=', $value);
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

        if (Arr::get($input, 'valid_from', false)) {
            $query->validFrom($input['valid_from']);
        }

        if (Arr::get($input, 'valid_from', false)) {
            $query->validTo($input['valid_from']);
        }

        if (Arr::get($input, 'status', false)) {
            $query->status($input['status']);
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

        $sortable = ['name', 'valid_from', 'valid_to', 'is_active', 'is_percentage'];

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
