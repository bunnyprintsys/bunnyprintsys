<?php

namespace App\Jobs;

use App\Models\SmsLog;
use App\Services\SmsService;
use App\Traits\FilterPhoneNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class SendSmsJob
 * @package App\Jobs
 */
class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FilterPhoneNumber;

    protected $data, $smsService;

    /**
     * @var int
     */
    public $tries = 1;

    /**
     * SendSmsJob constructor.
     * @param $metadata
     */
    public function __construct($metadata)
    {
        $this->data = $metadata;
        $this->smsService = new SmsService();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $phoneNumber = $this->removePhoneNumberPlus($this->data['destination']);

        $this->smsService->sendByISMS($phoneNumber, $this->data['subject']);

        if (isset($this->data['code'])) {
            $this->smsService->updateSmsStatus($this->data['code'], true, null);
        }
    }

    /**
     * @param \Exception $exception
     * @throws \Exception
     */
    public function failed(\Exception $exception)
    {
        if (isset($this->data['code'])) {
            $this->smsService->updateSmsStatus($this->data['code'], false, $exception->getMessage() . "\n\n" . $exception->getTraceAsString());
        }
    }
}
