<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    const STATUS_PENDING = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_INACTIVE = 88;
    const STATUS_REJECTED = 99;

    const ID_NRIC = 1;
    const ID_PASSPORT = 2;
    const ID_OTHER = 99;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone_number', 'alt_phone_number', 'id_type', 'id_value',
        'email', 'password', 'typeable_id', 'typeable_type', 'status',
        'creator_id', 'updater_id', 'profile_id', 'phone_country_id', 'alt_phone_country_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // relationships
    public function phoneCountry()
    {
        return $this->belongsTo(Country::class, 'phone_country_id');
    }

    public function altPhoneCountry()
    {
        return $this->belongsTo(Country::class, 'alt_phone_country_id');
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function typeable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function created_by()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updater_id');
    }

    // setter
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
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
     * @param $value
     * @param bool $like
     * @return mixed
     */
    public function scopeName($query, $value, $like = true)
    {
        $columnName = $this->getAliasColumnName('name');

        if ($like) {
            return $query->where($columnName, 'LIKE', '%'.$value.'%');
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
     * @param $value
     * @param bool $like
     * @return mixed
     */
    public function scopeEmail($query, $value, $like = true)
    {
        $columnName = $this->getAliasColumnName('email');

        if ($like) {
            return $query->where($columnName, 'LIKE', '%'.$value.'%');
        }
        return $query->where($columnName, $value);
    }

    /**
     * @param $query
     * @param $value
     * @param bool $like
     * @return mixed
     */
    public function scopePhoneNumber($query, $value, $like = true)
    {
        $columnName = $this->getAliasColumnName('phone_number');

        if ($like) {
            return $query->where($columnName, 'LIKE', '%'.$value.'%');
        }
        return $query->where($columnName, $value);
    }

    public function scopeStatus($query, $value)
    {
        $columnName = $this->getAliasColumnName('status');
        if (is_array($value)) {
            return $query->whereIn($columnName, $value);
        }
        return $query->where($columnName, $value);
    }


    public function scopeFilter($query, $input, $alias = null, $like = true)
    {
        if ($alias) {
            $this->alias = $alias;
        }

        if (Arr::get($input, 'id', false)) {
            $query->id($input['id']);
        }

        if (Arr::get($input, 'profile_id', false)) {
            $query->profileId($input['profile_id']);
        }

        if (Arr::has($input, 'status')) {
            $query->status($input['status']);
        }

        if (Arr::get($input, 'name', false)) {
            $query->name($input['name'], $like);
        }

        if (Arr::get($input, 'email', false)) {
            $query->email($input['email'], $like);
        }

        if (Arr::get($input, 'phone_number', false)) {
            $query->phoneNumber($input['phone_number'], $like);
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

    /**
     * @return mixed
     */
    public function isActive()
    {
        return $this->status === 1;
    }
}
