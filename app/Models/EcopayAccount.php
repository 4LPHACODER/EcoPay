<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcopayAccount extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'ecopay_accounts';

    protected $fillable = [
        'id',
        'email',
        'overall_bottles',
        'plastic_bottles',
        'metal_bottles',
        'coins_available',
    ];

    protected $casts = [
        'id' => 'string',
        'overall_bottles' => 'integer',
        'plastic_bottles' => 'integer',
        'metal_bottles' => 'integer',
        'coins_available' => 'integer',
    ];

    public function activityLogs()
    {
        return $this->hasMany(EcopayActivityLog::class, 'account_id', 'id');
    }
}
