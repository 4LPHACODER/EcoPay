<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\EcopayAccount;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Fetch or create ecopay account for the authenticated user
        $account = EcopayAccount::firstOrCreate(
            ['email' => $user->email],
            [
                'overall_bottles' => 0,
                'plastic_bottles' => 0,
                'metal_bottles' => 0,
                'coins_available' => 0,
            ]
        );

        // Fetch activity logs (latest 10)
        $logs = $account->activityLogs()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', [
            'account' => $account,
            'logs' => $logs,
        ]);
    }

    /**
     * Get dashboard data as JSON for real-time updates.
     */
    public function data(): JsonResponse
    {
        $user = Auth::user();

        $account = EcopayAccount::where('email', $user->email)->first();

        if (! $account) {
            return response()->json([
                'account' => null,
                'logs' => [],
            ]);
        }

        $logs = $account->activityLogs()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'account' => [
                'id' => $account->id,
                'email' => $account->email,
                'overall_bottles' => $account->overall_bottles,
                'plastic_bottles' => $account->plastic_bottles,
                'metal_bottles' => $account->metal_bottles,
                'coins_available' => $account->coins_available,
            ],
            'logs' => $logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'bottle_type' => $log->bottle_type,
                    'description' => $log->description,
                    'coins_earned' => $log->coins_earned,
                    'created_at' => $log->created_at->toIso8601String(),
                ];
            }),
        ]);
    }
}