<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class JobTicket extends Model
{
    protected $fillable = [
        'code', 'doc_no', 'doc_date', 'qty', 'remarks',
        'customer_id', 'product_id', 'status_id'
    ];

    // relationships
    // public function excelUploads()
    // {
    // return $this->morphMany(ExcelUpload::class, 'modelable')->latest()->take(5);
    // }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
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

    public function scopeCode($query, $value, $like = true)
    {
        $columnName = $this->getAliasColumnName('code');

        if (is_array($value)) {
            return $query->whereIn($columnName, $value);
        }
        if ($like) {
            return $query->where($columnName, 'LIKE', '%'.$value.'%');
        }
        return $query->where($columnName, $value);
    }

    public function scopeDocNo($query, $value, $like = true)
    {
        $columnName = $this->getAliasColumnName('doc_no');

        if (is_array($value)) {
            return $query->whereIn($columnName, $value);
        }
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

    public function scopeDateFrom($query, $value)
    {
        $columnName = $this->getAliasColumnName('doc_date');

        return $query->whereDate($columnName, '>=', $value);
    }

    public function scopeDateTo($query, $value)
    {
        $columnName = $this->getAliasColumnName('doc_date');

        return $query->whereDate($columnName, '<=', $value);
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

        if (Arr::get($input, 'code', false)) {
            $query->code($input['code'], $like);
        }

        if (Arr::get($input, 'doc_no', false)) {
            $query->docNo($input['doc_no'], $like);
        }

        if (Arr::get($input, 'status', false)) {
            $query->status($input['status'], $like);
        }

        if (Arr::get($input, 'date_from', false)) {
            $query->dateFrom($input['date_from']);
        }

        if (Arr::get($input, 'date_to', false)) {
            $query->dateTo($input['date_to']);
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

        $sortable = ['code', 'doc_no', 'doc_date', 'status', 'product_code', 'product_name', 'remarks'];

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
