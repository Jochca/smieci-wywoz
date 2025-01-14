<?php

return [
    "sns" => [
        "region" => env("AWS_SNS_REGION"),
        "version" => env("AWS_SNS_VERSION"),

        "sender_id" => env("AWS_SNS_SENDER_ID"),

        "credentials" => [
            "key" => env("AWS_SNS_KEY"),
            "secret" => env("AWS_SNS_SECRET"),
        ],
    ],
];