<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeName($query, $value)
    {
        return $query->where('name', $value);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeAgency($query)
    {
        return $query->name('agency');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeSuperAdmin($query)
    {
        return $query->name('super-admin');
    }
}
