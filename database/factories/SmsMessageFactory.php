<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SmsMessage>
 */
class SmsMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recipient' => $this->faker->e164PhoneNumber(),
            'message' => $this->faker->sentence(),
            'status' => 'pending',
            'sent_at' => null,
            'external_id' => null,
            'error_message' => null,
        ];
    }
}
