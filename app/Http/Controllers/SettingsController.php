<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EcopayAccount;
use App\Models\EcopayActivityLog;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $account = EcopayAccount::where('email', $user->email)->first();

        return view('settings', [
            'user' => $user,
            'account' => $account,
            'preference' => $user->display_preference ?? 'light',
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'display_preference' => 'nullable|in:light,dark,system',
        ]);

        $user = Auth::user();
        $user->update([
            'display_preference' => $request->input('display_preference', 'light'),
        ]);

        return back()->with('success', 'Preferences updated.');
    }

    public function resetCounters(Request $request)
    {
        $user = Auth::user();
        $account = EcopayAccount::where('email', $user->email)->first();

        if (! $account) {
            return back()->with('error', 'EcoPay account not found.');
        }

        // Simple guard: require confirmation parameter
        if ($request->input('confirm') !== 'yes') {
            return back()->with('error', 'Please confirm the reset by checking the confirmation box.');
        }

        $account->update([
            'overall_bottles' => 0,
            'plastic_bottles' => 0,
            'metal_bottles' => 0,
            'coins_available' => 0,
        ]);

        return back()->with('success', 'EcoPay counters reset to 0.');
    }

    /**
     * Decrease plastic bottle count.
     */
    public function decrementPlastic(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1|max:100',
        ]);

        $user = Auth::user();
        $account = EcopayAccount::where('email', $user->email)->first();

        if (! $account) {
            return back()->with('error', 'EcoPay account not found.');
        }

        $amount = $request->input('amount');

        $account->update([
            'plastic_bottles' => max($account->plastic_bottles - $amount, 0),
            'overall_bottles' => max($account->overall_bottles - $amount, 0),
        ]);

        // Log the adjustment
        EcopayActivityLog::create([
            'account_id' => $account->id,
            'bottle_type' => 'plastic',
            'description' => "Admin adjusted plastic count by -{$amount}",
            'coins_earned' => 0,
        ]);

        return back()->with('success', "Plastic bottles decreased by {$amount}.");
    }

    /**
     * Decrease metal bottle count.
     */
    public function decrementMetal(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1|max:100',
        ]);

        $user = Auth::user();
        $account = EcopayAccount::where('email', $user->email)->first();

        if (! $account) {
            return back()->with('error', 'EcoPay account not found.');
        }

        $amount = $request->input('amount');

        $account->update([
            'metal_bottles' => max($account->metal_bottles - $amount, 0),
            'overall_bottles' => max($account->overall_bottles - $amount, 0),
        ]);

        // Log the adjustment
        EcopayActivityLog::create([
            'account_id' => $account->id,
            'bottle_type' => 'metal',
            'description' => "Admin adjusted metal count by -{$amount}",
            'coins_earned' => 0,
        ]);

        return back()->with('success', "Metal bottles decreased by {$amount}.");
    }

    /**
     * Add coins to account.
     */
    public function addCoins(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1|max:1000',
        ]);

        $user = Auth::user();
        $account = EcopayAccount::where('email', $user->email)->first();

        if (! $account) {
            return back()->with('error', 'EcoPay account not found.');
        }

        $amount = $request->input('amount');

        $account->update([
            'coins_available' => $account->coins_available + $amount,
        ]);

        // Log the adjustment
        EcopayActivityLog::create([
            'account_id' => $account->id,
            'bottle_type' => 'metal',
            'description' => "Admin added {$amount} coins",
            'coins_earned' => $amount,
        ]);

        return back()->with('success', "Added {$amount} coins to your account.");
    }
}
