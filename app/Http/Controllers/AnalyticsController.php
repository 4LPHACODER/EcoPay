<?php

namespace App\Http\Controllers;

use App\Models\EcopayAccount;
use App\Models\EcopayActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $account = EcopayAccount::where('email', $user->email)->first();

        // Primary data points (fallback to zeros)
        $plastic = $account->plastic_bottles ?? 0;
        $metal = $account->metal_bottles ?? 0;
        $overall = $account->overall_bottles ?? ($plastic + $metal);
        $coins = $account->coins_available ?? 0;

        // Bar / doughnut datasets
        $barData = [
            'labels' => ['Plastic', 'Metal'],
            'datasets' => [
                [
                    'label' => 'Bottles',
                    'backgroundColor' => ['#7c3aed', '#374151'],
                    'data' => [$plastic, $metal],
                ],
            ],
        ];

        $doughnutData = [
            'labels' => ['Plastic', 'Metal'],
            'datasets' => [
                [
                    'backgroundColor' => ['#7c3aed', '#374151'],
                    'data' => [$plastic, $metal],
                ],
            ],
        ];

        // Line chart: coins trend — try to derive from activity logs grouped by date.
        // Fallback: single point for today with current coins.
        $lineLabels = [];
        $lineDataPoints = [];

        if ($account) {
            // Try to use activity logs if model/table exists
            try {
                $rows = EcopayActivityLog::query()
                    ->selectRaw('DATE(created_at) as day, SUM(coins_earned) as coins_sum')
                    ->where('account_id', $account->id)
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get();

                if ($rows->isNotEmpty()) {
                    $cumulative = 0;
                    foreach ($rows as $r) {
                        $lineLabels[] = $r->day;
                        $cumulative += (int) $r->coins_sum;
                        $lineDataPoints[] = $cumulative;
                    }
                }
            } catch (\Throwable $e) {
                // table/column may not exist — ignore and fallback below
            }
        }

        if (empty($lineLabels)) {
            $today = now()->toDateString();
            $lineLabels = [$today];
            $lineDataPoints = [$coins];
        }

        $lineData = [
            'labels' => $lineLabels,
            'datasets' => [
                [
                    'label' => 'Coins available',
                    'borderColor' => '#F59E0B',
                    'backgroundColor' => 'rgba(245,158,11,0.08)',
                    'data' => $lineDataPoints,
                    'fill' => true,
                ],
            ],
        ];

        return view('analytics', [
            'account' => $account,
            'barData' => $barData,
            'doughnutData' => $doughnutData,
            'lineData' => $lineData,
        ]);
    }
}
