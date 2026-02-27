<?php

namespace App\Services;

use App\Models\EcopayAccount;
use App\Models\EcopayActivityLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EcopayService
{
    /**
     * Record a bottle deposit for the given user email.
     *
     * @param string $email
     * @param string $bottleType 'plastic'|'metal'
     * @param int $coinsEarned
     * @param string|null $description
     *
     * @return EcopayActivityLog
     */
    public function recordBottleDeposit(string $email, string $bottleType, int $coinsEarned = 0, ?string $description = null): EcopayActivityLog
    {
        $bottleType = strtolower($bottleType);
        if (! in_array($bottleType, ['plastic', 'metal'])) {
            throw new \InvalidArgumentException('bottle_type must be "plastic" or "metal".');
        }

        return DB::transaction(function () use ($email, $bottleType, $coinsEarned, $description) {
            $account = EcopayAccount::where('email', $email)->lockForUpdate()->first();

            if (! $account) {
                // If account does not exist, create a fresh one (listener normally creates on register)
                $account = EcopayAccount::create([
                    'id' => (string) Str::uuid(),
                    'email' => $email,
                    'overall_bottles' => 0,
                    'plastic_bottles' => 0,
                    'metal_bottles' => 0,
                    'coins_available' => 0,
                ]);
            }

            // Increment counters
            $account->overall_bottles += 1;
            if ($bottleType === 'plastic') {
                $account->plastic_bottles += 1;
            } else {
                $account->metal_bottles += 1;
            }
            $account->coins_available += $coinsEarned;
            $account->save();

            // Create log
            $log = EcopayActivityLog::create([
                'id' => (string) Str::uuid(),
                'account_id' => $account->id,
                'bottle_type' => $bottleType,
                'coins_earned' => $coinsEarned,
                'description' => $description,
                'created_at' => Carbon::now(),
            ]);

            return $log;
        });
    }
}