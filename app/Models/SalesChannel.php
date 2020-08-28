<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class SalesChannel extends Model
{
    protected $fillable = [
        'name', 'desc'
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

    public function scopeName($query, $value, $like = true)
    {
        $columnName = $this->getAliasColumnName('name');

        if ($like) {
            return $query->where($columnName, 'LIKE', '%'.$value.'%');
        }
        return $query->where($columnName, $value);
    }

    public function scopeDesc($query, $value, $like = true)
    {
        $columnName = $this->getAliasColumnName('desc');

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

        if (Arr::get($input, 'desc', false)) {
            $query->symbol($input['desc'], $like);
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

        $sortable = ['name', 'desc'];

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
