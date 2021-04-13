<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Customer extends Model
{
    protected $fillable = [
        'is_company', 'company_name', 'roc', 'latest_otp',
        'is_verified', 'profile_id', 'payment_term_id', 'code', 'name'

    ];

    // relationships
    public function addresses()
    {
        return $this->morphMany(Address::class, 'typeable');
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function user()
    {
        return $this->morphOne(User::class, 'typeable');
    }

    // setter
    public function setIsCompanyAttribute($value)
    {
        $this->attributes['is_company'] = $value == 'true' ? 1 : 0;
    }

    public function setProfileIdAttribute($value)
    {
        $this->attributes['profile_id'] = $value ? $value : auth()->user()->profile->id;
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

    public function scopeProfileId($query, $value)
    {
        $columnName = $this->getAliasColumnName('profile_id');

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
            $query->whereHas('user', function ($query) use ($input) {
                return $query->filter([
                    'name' => $input['name']
                ]);
            });
        }

        if (Arr::get($input, 'company_name', false)) {
            $query->name($input['company_name'], $like);
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

        $sortable = ['id', 'users.name', 'is_company', 'phone_number', 'alt_phone_number', 'email', 'status', 'company_name'];

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
