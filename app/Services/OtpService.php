<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    // create random otp
    public function createRandom($input)
    {
        return $this->getRandomDigits($input);
    }

    // store into session
    public function storeSession($input)
    {
        $result = Hash::make($input);
        session(['otp' => $result]);

        return $result;
    }

    // compare hash input with session stored
    public function validateSession($input)
    {
        if(Hash::check($input, session('otp'))) {
            return true;
        }else {
            return false;
        }
    }

    // generate random digits
    protected function getRandomDigits($input)
    {
        return rand(pow(10, $input-1), pow(10, $input)-1);
    }
}
