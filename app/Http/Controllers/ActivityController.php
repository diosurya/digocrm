<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Customer;
use App\Models\Lead;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Activity::with(['user', 'activitable']);

        if ($user->role !== 'superadmin') {
            $query->where('user_id', $user->id);
        }

        $activities = $query->latest()->paginate(20);
        return view('activities.index', compact('activities'));
    }

    public function create(Request $request)
    {
        $targetId = $request->target_id;
        $targetType = $request->target_type; // 'Lead' or 'Customer'
        
        $user = auth()->user();
        $leads = Lead::where('user_id', $user->id)->get();
        $customers = Customer::where('user_id', $user->id)->get();

        return view('activities.create', compact('leads', 'customers', 'targetId', 'targetType'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activitable_id' => 'required|uuid',
            'activitable_type' => 'required|string',
            'activity_type' => 'required|in:CALL,WHATSAPP,EMAIL,MEETING,VISIT,DEMO,NOTE',
            'result' => 'required|string',
            'outcome' => 'required|string',
            'status' => 'required|in:OPEN,PENDING,DONE,MISSED,CANCELLED',
            'next_followup_at' => 'nullable|date|after:now',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['followup_date'] = now();

        $activity = Activity::create($validated);

        // Update target (Lead/Customer) next_followup
        $target = $validated['activitable_type']::find($validated['activitable_id']);
        if ($target && $request->filled('next_followup_at')) {
            $target->update(['next_followup_at' => $request->next_followup_at]);
        }

        return redirect()->route('activities.index')->with('success', 'Activity logged successfully.');
    }
}
