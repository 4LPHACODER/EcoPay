<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsMessage extends Model
{
    /** @use HasFactory<\Database\Factories\SmsMessageFactory> */
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'recipient',
        'message',
        'status',
        'sent_at',
        'external_id',
        'error_message',
    ];
}
