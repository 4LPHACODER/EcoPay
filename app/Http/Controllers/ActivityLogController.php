<?php

namespace App\Http\Controllers;

use App\Models\EcopayActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Delete an activity log.
     */
    public function delete(string $id)
    {
        $log = EcopayActivityLog::findOrFail($id);

        // Ensure user owns this log
        $account = $log->account;
        if ($account->email !== Auth::user()->email) {
            abort(403, 'Unauthorized');
        }

        $log->delete();

        return redirect()->route('dashboard')->with('success', 'Log deleted.');
    }
}