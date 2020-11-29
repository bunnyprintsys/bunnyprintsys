<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Carbon\Carbon;


class Admin extends Model
{

    protected $fillable = [
        'join_date', 'leave_date', 'profile_id'
    ];

    // relationships
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function user()
    {
        return $this->morphOne(User::class, 'typeable');
    }

    // getter
    public function getJoinDateAttribute($value)
    {
        return Carbon::parse($value)->toDateString();
    }

    public function getLeaveDateAttribute($value)
    {
        return Carbon::parse($value)->toDateString();
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

    // filter
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
            $query->whereHas('user', function ($query) use ($input) {
                return $query->filter([
                    'name' => $input['name']
                ]);
            });
        }

        if (Arr::get($input, 'email', false)) {
            $query->whereHas('user', function ($query) use ($input) {
                return $query->filter([
                    'email' => $input['email']
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

        if (Arr::get($input, 'profile_id', false)) {
            $query->profileId($input['profile_id']);
        }

        if (Arr::get($input, 'status', false)) {
            $query->whereHas('user', function ($query) use ($input) {
                return $query->filter([
                    'status' => $input['status']
                ]);
            });
        }

        return $query;
    }

    // sort by
    public function scopeSortBy($query, $input, $alias = null)
    {
        if ($alias) {
            $this->alias = $alias;
        }

        $sortable = ['id', 'phone_number', 'alt_phone_number', 'email', 'status'];

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
