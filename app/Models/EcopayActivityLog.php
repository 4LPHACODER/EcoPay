<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcopayActivityLog extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'ecopay_activity_logs';

    public $timestamps = false; // we will manage created_at manually

    protected $fillable = [
        'id',
        'account_id',
        'bottle_type',
        'coins_earned',
        'description',
        'created_at',
    ];

    protected $casts = [
        'id' => 'string',
        'account_id' => 'string',
        'coins_earned' => 'integer',
        'created_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(EcopayAccount::class, 'account_id', 'id');
    }
}