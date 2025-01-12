<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsAuthenticationCode extends Model
{
    protected $fillable = [
        "code",
        "action",
        "expires_at",
    ];

    protected $casts = [
        "expires_at" => "datetime",
    ];

    /** User that can use this code. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
