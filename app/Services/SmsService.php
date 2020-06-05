<?php

namespace App\Services;

use App\Jobs\SendSmsJob;
use App\Models\Profile;
use App\Models\SmsLog;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Class SmsService
 * @package App\Services
 */
class SmsService
{

    /**
     * @param $subject
     * @param $recipient Tenant
     * @param $template
     * @param Profile $profile
     * @param Model|null $triggerable
     * @param Model|null $sender
     * @throws \Exception
     */
    public function sendSms($subject, $recipient, $template, Profile $profile, Model $triggerable = null, Model $sender = null)
    {
        try {
            $phoneNumber = $recipient->getPhoneNumber();

            if (empty($phoneNumber)) {
                throw new \Exception('Phone number not found');
            }

            if (!SmsLog::isTemplateExisted($template)) {
                throw new \Exception('Template not found');
            }

            $data = [];

            if (App::environment('local')) {
                $subject = '[Sandbox] ' . $subject;
            }

            $data['subject'] = $subject;
            $data['provider'] = SmsLog::PROVIDER_ISMS;
            $data['template'] = $template;
            $data['recipient_type'] = get_class($recipient);
            $data['recipient_id'] = $recipient->id;
            $data['destination'] = $phoneNumber;
            $data['is_success'] = false;
            $data['profile_id'] = $profile->id;

            if ($sender) {
                $data['sender_type'] = get_class($sender);
                $data['sender_id'] = $sender->id;
            }
            if ($triggerable) {
                $data['triggerable_type'] = get_class($triggerable);
                $data['triggerable_id'] = $triggerable->id;
            }

            $data['code'] = self::generateHash($phoneNumber, $data['subject']);

            if ($template === SmsLog::TEMPLATE_ARC_FORM) {
                $data['options'] = [
                    'arcId' => $triggerable->id
                ];
            }

            $data['metadata'] = $data;
            SmsLog::create($data);
            SendSmsJob::dispatch($data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $code
     * @param $success
     * @param null $error
     * @return mixed
     * @throws \Exception
     */
    public function updateSmsStatus($code, $success, $error = null)
    {
        $email = SmsLog::where('code', $code)->first();
        if ($email == null) {
            throw new \Exception('Cannot find sms log.');
        }
        if ($success === true) {
            $email->is_success = true;
            $email->sent_at = Carbon::now(config('app.timezone'));
        } else {
            $email->is_success = false;
            $email->error = $error;
        }
        $email->save();
        return $email;
    }

    /**
     * @param $destination
     * @param $subject
     * @return string
     */
    protected static function generateHash($destination, $subject)
    {
        return hash('sha256', $destination . $subject . time());
    }

    /**
     * @param $numbers
     * @param $content
     * @return int
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendByISMS($numbers, $content)
    {
        try {
            if (!$numbers) {
                throw new \Exception('Number(s) must be define');
            }

            if (!$content) {
                throw new \Exception('Content must be define');
            }

            if (!is_array($numbers)) {
                $numbers = [$numbers];
            }

            if (count($numbers) > 300) {
                throw new \Exception('Maximum 300 number per push');
            }

            $client = new Client([
                'base_uri' => config('isms.API_URL'),
                'timeout' => 30
            ]);
            $payload = [
                'sendid' => urlencode(config('isms.USERNAME')),
                'un' => urlencode(config('isms.USERNAME')),
                'pwd' => urlencode(config('isms.PASSWORD')),
                'dstno' => implode(';', $numbers),
                'msg' => $content,
                'agreedterm' => 'YES',
                'type' => strlen($content) != strlen(utf8_decode($content)) ? 2 : 1
            ];
            // 1 - ASCII (English, Bahasa Melayu, etc); 2 - Unicode (Chinese, Japanese, etc)
            $response = $client->request('GET', '/isms_send.php', [
                'query' => $payload
            ]);
            $result = $response->getBody()->getContents();

            if ($result != '2000 = SUCCESS' && !empty($result)) {
                throw new \Exception('ISMS: ' . $result);
            }
            return count($numbers);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getiSMSBalanceCredit()
    {
        $client = new Client([
            'base_uri' => config('isms.API_URL'),
            'timeout' => 30
        ]);
        $payload = [
            'un' => urlencode(config('isms.USERNAME')),
            'pwd' => urlencode(config('isms.PASSWORD')),
        ];
        $response = $client->request('GET', '/isms_balance.php', [
            'query' => $payload
        ]);
        $result = $response->getBody()->getContents();
        return $result;
    }
}
