<?php

namespace App\Services\SmsDeliveryService\Concrete;

use App\Services\SmsDeliveryService\ISmsDeliveryService;
use Illuminate\Support\Collection;
use Smsapi\Client\Feature\Sms\Bag\SendSmssBag;
use Smsapi\Client\Feature\Sms\SmsFeature;
use Smsapi\Client\SmsapiHttpClient;

/**
 * Implementation of Service for delivering SMS messages using `SMSapi.pl` service.
 */
class SmsApiSmsDeliveryService implements ISmsDeliveryService
{
    protected SmsFeature $smsFeature;

    public function __construct(
        protected SmsapiHttpClient $client,
    ) {
        $this->smsFeature = $this->client
            ->smsapiPlService(config("app.smsapi_pl.api_key"))
            ->smsFeature();
    }

    /** Send SMS message to recipient(s). */
    public function sendSms(string|array|Collection $recipients, string $message): void
    {
        if (is_string($recipients)) {
            $recipients = [$recipients];
        }

        if (is_array($recipients)) {
            $recipients = collect($recipients);
        }

        $recipients = $recipients->map(fn($recipient) => $this->formatPhoneNumber($recipient));

        $smssBag = SendSmssBag::withMessage($recipients->toArray(), $message);

        $this->smsFeature->sendSmss($smssBag);
    }

    /** Formats phone number. */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        $phoneNumber = substr($phoneNumber, -9);
        $phoneNumber = "48" . $phoneNumber;

        if (strlen($phoneNumber) !== 11) {
            throw new \InvalidArgumentException("Invalid phone number format, expected: 48XXXXXXXXX, got: $phoneNumber");
        }

        return $phoneNumber;
    }
}