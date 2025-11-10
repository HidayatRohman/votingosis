<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class VotingScheduleController extends Controller
{
    /**
     * Update voting schedule (start and end datetime).
     */
    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'voting_start_at' => ['required', 'date'],
            'voting_end_at' => ['required', 'date', 'after:voting_start_at'],
        ]);

        $start = Carbon::parse($data['voting_start_at']);
        $end = Carbon::parse($data['voting_end_at']);

        Setting::set('voting_start_at', $start->toDateTimeString());
        Setting::set('voting_end_at', $end->toDateTimeString());

        return back()->with('status', 'Voting schedule updated successfully.');
    }
}