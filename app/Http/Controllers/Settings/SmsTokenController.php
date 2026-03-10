<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
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

        return view('sms-token', [
            'token' => $token,
            'getEndpoint' => url('/api/sms/pending'),
            'putEndpoint' => url('/api/sms/123/sent'),
        ]);
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
