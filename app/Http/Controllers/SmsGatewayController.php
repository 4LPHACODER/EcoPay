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
            ->get()
            ->map(fn (SmsMessage $sms) => [
                'id' => $sms->id,
                'phone_number' => $sms->recipient,
                'message' => $sms->message,
                'status' => $sms->status,
                'created_at' => $sms->created_at,
            ]);

        return response()->json(['data' => $messages]);
    }

    public function markSent(Request $request, SmsMessage $smsMessage): JsonResponse
    {
        $this->ensureAuthorized($request);

        $request->validate([
            'external_id' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:sent,failed'],
        ]);

        $smsMessage->forceFill([
            'status' => 'sent',
            'sent_at' => now(),
            'external_id' => $request->input('external_id', $smsMessage->external_id),
        ])->save();

        return response()->json([
            'id' => $smsMessage->id,
            'phone_number' => $smsMessage->recipient,
            'message' => $smsMessage->message,
            'status' => $smsMessage->status,
            'sent_at' => $smsMessage->sent_at,
        ]);
    }

    protected function ensureAuthorized(Request $request): void
    {
        // Accept both "Authorization: Bearer <token>" (Flutter app)
        // and the legacy "X-Api-Token: <token>" header.
        $tokenValue = $this->extractToken($request);

        if ($tokenValue === '') {
            abort(response()->json(
                ['message' => 'Missing Authorization header. Expected: Authorization: Bearer <token>'],
                Response::HTTP_UNAUTHORIZED,
            ));
        }

        $token = SmsToken::query()
            ->where('token', $tokenValue)
            ->where('active', true)
            ->first();

        if (! $token) {
            abort(response()->json(
                ['message' => 'Invalid or inactive API token.'],
                Response::HTTP_UNAUTHORIZED,
            ));
        }

        $token->forceFill(['last_used_at' => now()])->save();
    }

    private function extractToken(Request $request): string
    {
        // Primary: Authorization: Bearer <token>
        $bearer = $request->bearerToken();
        if ($bearer !== null && $bearer !== '') {
            return $bearer;
        }

        // Fallback: X-Api-Token: <token>
        return (string) $request->header('X-Api-Token', '');
    }
}
