<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Profile extends Model
{

    const TYPE_INDIVIDUAL = 1;
    const TYPE_COORPERATE = 2;

    const PROFILE_TYPE = [
        self::TYPE_INDIVIDUAL => 'Individual',
        self::TYPE_COORPERATE => 'Coorperate',
    ];

    const CURRENCY = [
        'MYR' => [
            'symbol' => 'RM',
            'country_code' => '60'
        ],
        'SGD' => [
            'symbol' => 'S$',
            'country_code' => '65'
        ]
    ];

    protected $fillable = [
        'name', 'roc', 'currency', 'address', 'country_code', 'user_id'
    ];

    // relationships
    public function taxes()
    {
        return $this->hasMany('App\Models\Tax');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
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

        if (Arr::get($input, 'name', false)) {
            $query->name($input['name'], $like);
        }

        if (Arr::get($input, 'roc', false)) {
            $query->name($input['roc'], $like);
        }

        if (Arr::get($input, 'email', false)) {
            $query->whereHas('user', function ($query) use ($input) {
                return $query->filter([
                    'email' => $input['email']
                ]);
            });
        }

        if (Arr::get($input, 'attn_name', false)) {
            $query->whereHas('user', function ($query) use ($input) {
                return $query->filter([
                    'name' => $input['name']
                ]);
            });
        }

        if (Arr::get($input, 'phone_number', false)) {
            $query->whereHas('user', function ($query) use ($input) {
                return $query->filter([
                    'phone_number' => $input['phone_number']
                ]);
            });
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

        $sortable = ['id', 'name', 'roc', 'users.name', 'phone_number', 'email'];

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
