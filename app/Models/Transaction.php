<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use App\Traits\RunningNumber;

class Transaction extends Model
{
    use RunningNumber;

    protected $fillable = [
        'order_date', 'job_id', 'job', 'cost', 'receiver_id',
        'is_artwork_provided', 'is_design_required', 'dispatch_date', 'status_id',
        'tracking_number', 'subtotal', 'grandtotal', 'remarks',
        'customer_id', 'admin_id', 'profile_id', 'invoice_id', 'invoice_number', 'sales_channel_id', 'delivery_method_id',
        'created_by', 'updated_by', 'designed_by', 'delivery_address_id', 'billing_address_id',
        'is_same_address', 'is_convert_invoice'
    ];

    // relationships
    public function deliveryAddress()
    {
        return $this->belongsTo(Address::class, 'delivery_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function deliveryMethod()
    {
        return $this->belongsTo(DeliveryMethod::class, 'delivery_method_id');
    }

    public function handler()
    {
        return $this->belongsTo('App\Models\User', 'admin_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function receiver()
    {
        return $this->belongsTo(Receiver::class);
    }

    public function salesChannel()
    {
        return $this->belongsTo(SalesChannel::class, 'sales_channel_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function transactionFiles()
    {
        return $this->hasMany(TransactionFile::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function designer()
    {
        return $this->belongsTo(User::class, 'designed_by');
    }

    // setter and getter
    public function setCostAttribute($value)
    {
        $this->attributes['cost'] = $value ? $value : 0;
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

    public function scopeInvoiceId($query, $value)
    {
        $columnName = $this->getAliasColumnName('invoice_id');

        if (is_array($value)) {
            return $query->whereIn($columnName, $value);
        }
        return $query->where($columnName, $value);
    }

    public function scopeIsConvertInvoice($query, $value)
    {
        $columnName = $this->getAliasColumnName('is_convert_invoice');

        return $query->where($columnName, $value);
    }

    public function scopeJobId($query, $value)
    {
        $columnName = $this->getAliasColumnName('job_id');

        if (is_array($value)) {
            return $query->whereIn($columnName, $value);
        }
        return $query->where($columnName, $value);
    }

    public function scopeRoc($query, $value)
    {
        $columnName = $this->getAliasColumnName('roc');

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

    public function scopeStatus($query, $value)
    {
        $columnName = $this->getAliasColumnName('status');

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

        if (Arr::get($input, 'customer_name', false)) {
            $query->whereHas('customer', function ($query) use ($input) {
                $query->whereHas('user', function ($query) use ($input) {
                    return $query->filter([
                        'name' => $input['customer_name']
                    ]);
                });
            });
        }

        if (Arr::get($input, 'customer_phone_number', false)) {
            $query->whereHas('customer', function ($query) use ($input) {
                $query->whereHas('user', function ($query) use ($input) {
                    return $query->filter([
                        'phone_number' => $input['customer_phone_number']
                    ]);
                });
            });
        }

        if (Arr::get($input, 'invoice_id', false)) {
            $query->invoiceId($input['invoice_id'], $like);
        }

        if (Arr::get($input, 'is_convert_invoice', false)) {
            $query->isConvertInvoice($input['is_convert_invoice']);
        }

        if (Arr::get($input, 'job_id', false)) {
            $query->jobId($input['job_id'], $like);
        }

        if (Arr::get($input, 'roc', false)) {
            $query->roc($input['roc'], $like);
        }

        if (Arr::get($input, 'profile_id', false)) {
            $query->profileId($input['profile_id']);
        }

        if (Arr::get($input, 'status', false)) {
            $query->status($input['status'], $like);
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
     * Make sure got DB transaction cover this function
     * @return string
     */
    public function generateNextJobId()
    {
        $number = $this->getRunningNumByYearMonth($this->job_id);
        $this->job_id = $number;
        $this->save();
        return $number;
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
