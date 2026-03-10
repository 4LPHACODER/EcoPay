<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\SmsMessage;
use App\Models\SmsToken;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SmsTokenController extends Controller
{
    public function edit()
    {
        $token = SmsToken::query()
            ->where('active', true)
            ->orderByDesc('id')
            ->first();

        $recentMessages = SmsMessage::query()
            ->latest()
            ->limit(5)
            ->get();

        return view('sms-token', [
            'token' => $token,
            'getEndpoint' => url('/api/sms/pending'),
            'putEndpoint' => url('/api/sms/{id}/sent'),
            'recentMessages' => $recentMessages,
        ]);
    }

    public function sendTest(Request $request)
    {
        $request->validate([
            'recipient' => ['required', 'string', 'max:20'],
            'message' => ['required', 'string', 'max:160'],
        ]);

        SmsMessage::create([
            'recipient' => $request->recipient,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()
            ->back()
            ->with('test_success', "Message queued for {$request->recipient}. The mobile gateway will pick it up on the next poll.");
    }

    public function rotate(Request $request)
    {
        SmsToken::query()->update(['active' => false]);

        $token = SmsToken::create([
            'name' => 'Primary mobile app token',
            'token' => Str::random(64),
            'active' => true,
        ]);

        return redirect()
            ->back()
            ->with('success', 'SMS API token rotated successfully.')
            ->with('sms_token', $token->token);
    }
}
