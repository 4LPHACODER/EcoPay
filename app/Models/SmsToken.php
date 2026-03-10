<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsToken extends Model
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'token',
        'active',
        'last_used_at',
    ];
}
