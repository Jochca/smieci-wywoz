<?php

use App\Services\UserAuthenticationService\IUserAuthenticationService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(fn() => app(IUserAuthenticationService::class)->cleanup())->dailyAt("20:19");
