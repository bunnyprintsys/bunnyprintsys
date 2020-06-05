<?php

namespace App\Interfaces;

/**
 * Interface SendSMSable
 * @package App\Interfaces
 */
interface SendSMSable
{
	public function getPhoneNumber();
	public function getName();
}
