<?php

use App\Models\SmsMessage;
use Illuminate\Support\Facades\Config;

use function Pest\Laravel\getJson;
use function Pest\Laravel\putJson;

it('returns pending sms messages when authorized', function () {
    Config::set('services.sms.api_token', 'test-token');

    $pending = SmsMessage::factory()->count(3)->create([
        'status' => 'pending',
    ]);

    $sent = SmsMessage::factory()->count(2)->create([
        'status' => 'sent',
    ]);

    $response = getJson('/api/sms/pending', [
        'X-Api-Token' => 'test-token',
    ]);

    $response->assertSuccessful();

    $response->assertJsonCount(3);

    $ids = $response->collect()->pluck('id')->all();

    expect($ids)->toMatchArray($pending->pluck('id')->all());
});

it('rejects unauthorized access to pending sms', function () {
    Config::set('services.sms.api_token', 'test-token');

    $response = getJson('/api/sms/pending');

    $response->assertUnauthorized();
});

it('marks an sms message as sent', function () {
    Config::set('services.sms.api_token', 'test-token');

    $message = SmsMessage::factory()->create([
        'status' => 'pending',
        'sent_at' => null,
    ]);

    $response = putJson("/api/sms/{$message->id}/sent", [
        'external_id' => 'provider-123',
    ], [
        'X-Api-Token' => 'test-token',
    ]);

    $response->assertSuccessful();

    $message->refresh();

    expect($message->status)->toBe('sent')
        ->and($message->sent_at)->not->toBeNull()
        ->and($message->external_id)->toBe('provider-123');
});
