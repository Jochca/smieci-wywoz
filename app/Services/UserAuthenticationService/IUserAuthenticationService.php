<?php

namespace App\Services\UserAuthenticationService;

use App\Models\User;

interface IUserAuthenticationService
{
    /**
     * Send SMS with authentication code to given user and execute given action after successful verification.
     *
     * @param array|string|null $action Callable action
     */
    public function sendSmsAuthCode(User $user, array|string|null $action = null);

    /** Verify given SMS authentication code for given user. */
    public function verify(User $user, string $code): bool;

    /**
     * Execute action that was associated with given SMS authentication code.
     * 
     * Should be called only after successful verification. (@see verify method)
     */
    public function executeAction(User $user, string $code): void;

    /** Cleans up any expired SMS authentication codes. */
    public function cleanup(): void;

    /** Generate random SMS authentication code. */
    public function generateCode(): string;
}