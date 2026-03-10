<?php

namespace App\Http\Controllers;

use App\Models\SmsMessage;
use App\Models\SmsToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SmsGatewayController extends Controller
{
    public function pending(Request $request): JsonResponse
    {
        $this->ensureAuthorized($request);

        $messages = SmsMessage::query()
            ->where('status', 'pending')
            ->orderBy('id')
            ->limit(100)
            ->get();

        return response()->json($messages);
    }

    public function markSent(Request $request, SmsMessage $smsMessage): JsonResponse
    {
        $this->ensureAuthorized($request);

        $validated = $request->validate([
            'external_id' => ['nullable', 'string', 'max:255'],
        ]);

        $smsMessage->forceFill([
            'status' => 'sent',
            'sent_at' => now(),
            'external_id' => $validated['external_id'] ?? $smsMessage->external_id,
        ])->save();

        return response()->json($smsMessage);
    }

    protected function ensureAuthorized(Request $request): void
    {
        $tokenValue = (string) $request->header('X-Api-Token');

        if ($tokenValue === '') {
            abort(Response::HTTP_UNAUTHORIZED, 'Invalid API token.');
        }

        $token = SmsToken::query()
            ->where('token', $tokenValue)
            ->where('active', true)
            ->first();

        if (! $token) {
            abort(Response::HTTP_UNAUTHORIZED, 'Invalid API token.');
        }

        $token->forceFill([
            'last_used_at' => now(),
        ])->save();
    }
}
