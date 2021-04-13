<?php

namespace App\Models;

use App\Traits\RunningNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Profile extends Model
{
    use RunningNumber;

    const TYPE_INDIVIDUAL = 1;
    const TYPE_COORPERATE = 2;

    const PROFILE_TYPE = [
        self::TYPE_INDIVIDUAL => 'Individual',
        self::TYPE_COORPERATE => 'Coorperate',
    ];

    protected $fillable = [
        'name', 'roc', 'address', 'user_id', 'country_id',
        'job_running_number', 'job_prefix', 'invoice_running_number', 'invoice_prefix'
    ];

    // relationships

    public function address()
    {
        return $this->morphOne(Address::class, 'typeable');
    }

    public function bankBinding()
    {
        return $this->morphOne(BankBinding::class, 'bankable');
    }

    public function taxes()
    {
        return $this->hasMany('App\Models\Tax');
    }

    public function user()
    {
        return $this->morphOne(User::class, 'typeable');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
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

        if ($like) {
            return $query->where($columnName, 'LIKE', '%'.$value.'%');
        }
        return $query->where($columnName, $value);
    }

    public function scopeRoc($query, $value, $like = true)
    {
        $columnName = $this->getAliasColumnName('roc');

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

        if (Arr::get($input, 'company_name', false)) {
            $query->name($input['company_name'], $like);
        }

        if (Arr::get($input, 'roc', false)) {
            $query->roc($input['roc'], $like);
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

    /**
     * Make sure got DB transaction cover this function
     * @return string
     */
    public function generateNextJobCode()
    {
        $number = $this->getRunningNumByYearMonth($this->job_running_number);
        $this->job_running_number = $number;
        $this->save();
        return $this->prefix . $number;
    }

    /**
     * Make sure got DB transaction cover this function
     * @return string
     */
    public function generateNextInvoiceId()
    {
        $number = $this->getRunningNumByYearMonth($this->invoice_running_number);
        $this->invoice_running_number = $number;
        $this->save();
        return $this->invoice_prefix . $number;
    }

}
