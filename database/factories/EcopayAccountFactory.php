<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\EcopayAccount;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EcopayAccount>
 */
class EcopayAccountFactory extends Factory
{
    protected $model = EcopayAccount::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'email' => $this->faker->unique()->safeEmail(),
            'overall_bottles' => 0,
            'plastic_bottles' => 0,
            'metal_bottles' => 0,
            'coins_available' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}