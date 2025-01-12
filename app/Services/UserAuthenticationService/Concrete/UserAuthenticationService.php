<?php

namespace App\Services\UserAuthenticationService\Concrete;

use App\Models\User;
use App\Services\SmsDeliveryService\ISmsDeliveryService;
use App\Services\UserAuthenticationService\IUserAuthenticationService;
use Carbon\Carbon;

class UserAuthenticationService implements IUserAuthenticationService
{
    /** Length of Sms Authentication code. */
    public const int SMS_AUTHENTICATION_CODE_LENGTH = 5;

    /** Expiration time of Sms Authentication code in minutes. */
    public const int SMS_AUTHENTICATION_CODE_EXPIRATION_MINUTES = 5;

    public function __construct(
        protected ISmsDeliveryService $smsDeliveryService,
    ) {
    }

    /**
     * Send SMS with authentication code to given user and execute given action after successful verification.
     *
     * @param array|string|null $action Callable action
     */
    public function sendSmsAuthCode(User $user, array|string|null $action = null): void
    {
        $code = $this->generateCode();

        if (!is_callable($action) && $action !== null) {
            throw new \InvalidArgumentException("Action must be callable.");
        }

        $user->smsAuthenticationCodes()->create([
            "code" => $code,
            "action" => $action === null ? null : json_encode($action),
            "expires_at" => Carbon::now()->addMinutes(self::SMS_AUTHENTICATION_CODE_EXPIRATION_MINUTES),
        ]);

        $this->smsDeliveryService->sendSms($user->phone_number, "Twój kod weryfikacyjny to: $code");
    }

    /** Verify given SMS authentication code for given user. */
    public function verify(User $user, string $code): bool
    {
        return $user->smsAuthenticationCodes()
            ->where("code", $code)
            ->where("expires_at", ">", Carbon::now())
            ->exists();
    }

    /**
     * Execute action that was associated with given SMS authentication code.
     * 
     * Should be called only after successful verification. (@see verify method)
     */
    public function executeAction(User $user, string $code): void
    {
        $smsAuthenticationCode = $user->smsAuthenticationCodes()
            ->where("code", $code)
            ->where("expires_at", ">", Carbon::now())
            ->first();

        if ($smsAuthenticationCode === null) {
            throw new \InvalidArgumentException("Invalid code.");
        }

        $action = json_decode($smsAuthenticationCode->action, true);

        if (!is_callable($action)) {
            throw new \InvalidArgumentException("Action must be callable.");
        }

        call_user_func($action, $user);

        $smsAuthenticationCode->delete();
    }

    /** Generate random SMS authentication code. */
    protected function generateCode(): string
    {
        $lowerBound = 0;
        $upperBound = 10 ** self::SMS_AUTHENTICATION_CODE_LENGTH - 1;

        return str_pad(random_int($lowerBound, $upperBound), 5, "0", STR_PAD_LEFT);
    }
}