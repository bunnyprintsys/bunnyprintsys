<?php

namespace App\Traits;



trait NumberFormatting
{
  public function removeTraillingZero($value){
    $pos = strpos($value, '.');
    if($pos === false) { // it is integer number
      return $value;
    }else{ // it is decimal number
      return rtrim(rtrim($value, '0'), '.');
    }
  }
}
