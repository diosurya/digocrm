<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Customer;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Lead Conversion Rate
        $totalLeads = Lead::count();
        $convertedLeads = Lead::where('status', 'WON')->count();
        $conversionRate = $totalLeads > 0 ? ($convertedLeads / $totalLeads) * 100 : 0;

        // 2. Activity Count (Last 30 Days)
        $activityStats = Activity::select('activity_type', DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('activity_type')
            ->get();

        // 3. Customer Growth
        $customerGrowth = Customer::select(
                DB::raw('MONTHNAME(created_at) as month'), 
                DB::raw('count(*) as total'),
                DB::raw('MIN(created_at) as sort_date')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('sort_date')
            ->get();

        // 4. Marketing KPI (Activities per User)
        $marketingKPI = User::whereIn('role', ['marketing', 'manager_marketing'])
            ->withCount(['activities' => function($q) {
                $q->where('created_at', '>=', now()->startOfMonth());
            }])
            ->get();

        return view('reports.index', compact(
            'totalLeads', 'convertedLeads', 'conversionRate', 
            'activityStats', 'customerGrowth', 'marketingKPI'
        ));
    }
}
