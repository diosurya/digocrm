<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Customer;
use App\Models\Task;
use App\Models\Activity;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // 1. Base Queries
        $queryLeads = Lead::query();
        $queryCustomers = Customer::query();
        $queryTasks = Task::where('status', 'pending');
        $queryActivities = Activity::with(['user', 'activitable']);

        // 2. Data Scoping (RBAC)
        if ($user->role !== 'superadmin') {
            if ($user->role === 'manager_marketing') {
                $subordinateIds = $user->subordinates()->pluck('id')->toArray();
                $userIds = array_merge([$user->id], $subordinateIds);
                
                $queryLeads->whereIn('user_id', $userIds);
                $queryCustomers->whereIn('user_id', $userIds);
                $queryTasks->whereIn('user_id', $userIds);
                $queryActivities->whereIn('user_id', $userIds);
            } else {
                $queryLeads->where('user_id', $user->id);
                $queryCustomers->where('user_id', $user->id);
                $queryTasks->where('user_id', $user->id);
                $queryActivities->where('user_id', $user->id);
            }
        }

        // 3. KPI Statistics
        $stats = [
            'total_lead' => (clone $queryLeads)->count(),
            'total_customer' => (clone $queryCustomers)->count(),
            'followup_today' => (clone $queryLeads)->whereDate('next_followup_at', today())->count(),
            'followup_overdue' => (clone $queryLeads)->where('next_followup_at', '<', now())->whereNotIn('status', ['WON', 'LOST', 'CONVERTED'])->count(),
            'customer_active' => (clone $queryCustomers)->where('status', 'ACTIVE')->count(),
            'activity_today' => (clone $queryActivities)->whereDate('created_at', today())->count(),
        ];

        // 4. Recent Data
        $recentActivities = $queryActivities->latest()->take(5)->get();
        $pendingTasks = $queryTasks->latest()->take(5)->get();

        // 5. Chart Data
        $chartData = ['labels' => [], 'data' => []];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chartData['labels'][] = $month->format('M');
            
            // Scoped count for chart
            $monthQuery = Customer::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month);
            if ($user->role !== 'superadmin') {
                if ($user->role === 'manager_marketing') {
                    $monthQuery->whereIn('user_id', $userIds);
                } else {
                    $monthQuery->where('user_id', $user->id);
                }
            }
            $chartData['data'][] = $monthQuery->count();
        }

        return view('dashboard', compact('stats', 'recentActivities', 'pendingTasks', 'chartData'));
    }
}
