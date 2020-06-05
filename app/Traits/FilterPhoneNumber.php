<?php

namespace App\Traits;

use Propaganistas\LaravelPhone\PhoneNumber;

trait FilterPhoneNumber
{
    /**
     * @param $value
     * @return PhoneNumber
     * @throws \Exception
     */
    public function filterPhoneNumberWithCountrySymbol($value, $phone_country_symbol)
    {
        try {
            if (empty($value)) {
                return $value;
            }
            $phone_number = PhoneNumber::make($value)->ofCountry($phone_country_symbol);
            return $phone_number;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

  public function removePhoneNumberPlus($value)
  {
    $phone_number = substr($value, 1);
    return $phone_number;
  }

  public function filterMalaysiaStandardPhoneNumber($value)
  {
    $phone_number = substr($value, 2);
    return $phone_number;
  }
}
