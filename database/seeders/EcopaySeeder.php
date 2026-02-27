<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\EcopayAccount;
use App\Services\EcopayService;
use Illuminate\Support\Str;

class EcopaySeeder extends Seeder
{
    public function run(): void
    {
        // Create a demo user if none exists
        $user = User::where('email', 'admin@ecopay.com')->first();
        
        if (! $user) {
            $user = User::create([
                'name' => 'Admin',
                'email' => 'admin@ecopay.com',
                'password' => bcrypt('admin123'),
            ]);
        }

        // Ensure ecopay account exists
        $account = EcopayAccount::firstOrCreate(
            ['email' => $user->email],
            [
                'id' => (string) Str::uuid(),
                'overall_bottles' => 0,
                'plastic_bottles' => 0,
                'metal_bottles' => 0,
                'coins_available' => 0,
            ]
        );

        // Use service to record 3 deposits: 2 plastic, 1 metal -> total coins 10
        $service = new EcopayService();

        // Example deposits
        $service->recordBottleDeposit($user->email, 'plastic', 4, 'Plastic Detected – The user drop a plastic in EcoPay and the cash is also drop');
        $service->recordBottleDeposit($user->email, 'plastic', 2, 'Plastic bottle recycled');
        $service->recordBottleDeposit($user->email, 'metal', 4, 'Metal bottle recycled');

        // After these deposits: overall_bottles = 3, plastic_bottles = 2, metal_bottles = 1, coins_available = 10
    }
}