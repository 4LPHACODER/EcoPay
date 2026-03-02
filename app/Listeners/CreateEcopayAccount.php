<?php

namespace App\Listeners;

use App\Models\EcopayAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class CreateEcopayAccount
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        if (! $user || ! isset($user->email)) {
            return;
        }

        // Create account if not exists
        EcopayAccount::firstOrCreate(
            ['email' => $user->email],
            [
                'id' => (string) Str::uuid(),
                'overall_bottles' => 0,
                'plastic_bottles' => 0,
                'metal_bottles' => 0,
                'coins_available' => 0,
            ]
        );
    }
}
