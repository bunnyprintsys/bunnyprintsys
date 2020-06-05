<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name', 'symbol', 'code', 'currency_name', 'currency_symbol'
    ];

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

    public function scopeName($query, $value)
    {
        $columnName = $this->getAliasColumnName('name');

        return $query->where($columnName, $value);
    }

    public function scopeSymbol($query, $value)
    {
        $columnName = $this->getAliasColumnName('symbol');

        return $query->where($columnName, $value);
    }

    public function scopeCode($query, $value)
    {
        $columnName = $this->getAliasColumnName('code');

        return $query->where($columnName, $value);
    }

    public function scopeCurrencyName($query, $value)
    {
        $columnName = $this->getAliasColumnName('currency_name');

        return $query->where($columnName, $value);
    }

    public function scopeCurrencyCode($query, $value)
    {
        $columnName = $this->getAliasColumnName('currency_code');

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

        if (Arr::get($input, 'symbol', false)) {
            $query->symbol($input['symbol']);
        }

        if (Arr::get($input, 'code', false)) {
            $query->code($input['code']);
        }

        if (Arr::get($input, 'currency_name', false)) {
            $query->currencyName($input['currency_name']);
        }

        if (Arr::get($input, 'currency_symbol', false)) {
            $query->currencySymbol($input['currency_symbol']);
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

        $sortable = ['id', 'name', 'symbol', 'code', 'currency_name', 'currency_symbol'];

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
