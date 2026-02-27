<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\EcopayActivityLog;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EcopayActivityLog>
 */
class EcopayActivityLogFactory extends Factory
{
    protected $model = EcopayActivityLog::class;

    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'account_id' => null, // set in seeder or factory state
            'bottle_type' => $this->faker->randomElement(['plastic', 'metal']),
            'coins_earned' => $this->faker->numberBetween(0, 10),
            'description' => $this->faker->sentence(),
            'created_at' => now(),
        ];
    }
}