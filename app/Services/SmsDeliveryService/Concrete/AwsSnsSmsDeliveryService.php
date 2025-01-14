<?php

namespace App\Services\SmsDeliveryService\Concrete;

use App\Services\SmsDeliveryService\ISmsDeliveryService;
use Illuminate\Support\Collection;
use Aws\Sns\SnsClient;

/**
 * Implementation of Service for delivering SMS messages using Amazon SNS service.
 */
class AwsSnsSmsDeliveryService implements ISmsDeliveryService
{

    public function __construct(
        protected SnsClient $client,
    ) {
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

        $this->client->SetSMSAttributes(
            [
                "attributes" => [
                    "DefaultSenderID" => config("aws.sns.sender_id"),
                    "DefaultSMSType" => "Transactional"
                ]
            ]
        );

        $recipients->each(fn($recipient) => $this->sendSmsToRecipient($recipient, $message));
    }

    /** Sends SMS message to single recipient. */
    protected function sendSmsToRecipient(string $recipient, string $message): void
    {
        $this->client->publish([
            "Message" => $message,
            "PhoneNumber" => $recipient,
        ]);
    }

    /**
     * Excludes opted out numbers from AWS SNS Dashboard from recipients list.
     * 
     * @param Collection<string> $recipients
     * @return Collection<string>
     */
    protected function excludeOptedOutNumbers(Collection $recipients): Collection
    {
        $optedOutNumbers = $this->client->listPhoneNumbersOptedOut()->get("phoneNumbers");

        return $recipients->filter(fn($recipient) => !in_array($recipient, $optedOutNumbers));
    }

    /** Formats phone number. */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        $phoneNumber = preg_replace("/[^0-9]/", "", $phoneNumber);
        $phoneNumber = substr($phoneNumber, -9);
        $phoneNumber = "48" . $phoneNumber;

        if (strlen($phoneNumber) !== 11) {
            throw new \InvalidArgumentException("Invalid phone number format, expected: 48XXXXXXXXX, got: $phoneNumber");
        }

        return $phoneNumber;
    }
}