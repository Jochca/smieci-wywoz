<?php

namespace App\Services\SmsDeliveryService;

use Illuminate\Database\Eloquent\Collection;

/**
 * Service for delivering SMS messages
 */
interface ISmsDeliveryService
{
    /** Send SMS message to recipient(s). */
    public function sendSms(string|array|Collection $recipient, string $message): void;
}