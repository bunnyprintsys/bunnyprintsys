<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    const TEMPLATE_REGISTRATION_LOGIN_OTP = 1;
    const TEMPLATE_STATUS_UPDATED = 2;

    const PROVIDER_ISMS = 1;

    /**
     * @var string
     */
    protected $table = 'sms_logs';

    /**
     * @var bool
     */
    protected $timestamp = true;
    /**
     * @var array
     */
    protected $casts = [
        'metadata' => 'array'
    ];
    /**
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'provider',
        'code',
        'sender_type',
        'sender_id',
        'recipient_type',
        'recipient_id',
        'triggerable_type',
        'triggerable_id',
        'profile_id',
        'destination',
        'subject',
        'metadata',
        'is_success',
        'error',
        'sent_at',
        'created_at',
        'updated_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function sender()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function recipient()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function triggerable()
    {
        return $this->morphTo();
    }

    /**
     * @param $value
     * @return bool
     */
    public static function isTemplateExisted($value)
    {
        return in_array($value, [
            self::TEMPLATE_REGISTRATION_LOGIN_OTP,
            self::TEMPLATE_STATUS_UPDATED,
            ]);
    }
}
