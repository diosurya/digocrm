<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enterprise Analytics & Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Top Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Lead Conversion Rate</p>
                    <p class="text-4xl font-black text-brand mt-1">{{ number_format($conversionRate, 1) }}%</p>
                    <p class="text-xs text-gray-400 mt-2">{{ $convertedLeads }} of {{ $totalLeads }} leads closed won.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Growth (Last 6 Months)</p>
                    <div class="flex items-end gap-2 mt-1">
                        @foreach($customerGrowth as $g)
                            <div class="flex-1 bg-brand/10 rounded-t-lg relative group" style="height: {{ $g->total * 10 }}px; min-height: 20px;">
                                <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-[8px] font-bold text-brand opacity-0 group-hover:opacity-100">{{ $g->total }}</div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center">New Customer Master acquisitions.</p>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Marketing Velocity</p>
                    <p class="text-4xl font-black text-gray-900 mt-1">{{ $marketingKPI->sum('activities_count') }}</p>
                    <p class="text-xs text-gray-400 mt-2">Total activities recorded this month.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Activity Breakdown -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-lg font-black text-gray-900 mb-6 uppercase tracking-tight">Communication Channel Distribution</h3>
                    <div class="space-y-4">
                        @foreach($activityStats as $stat)
                        <div class="space-y-1">
                            <div class="flex justify-between text-xs font-bold uppercase">
                                <span class="text-gray-500">{{ $stat->activity_type }}</span>
                                <span class="text-brand">{{ $stat->total }}</span>
                            </div>
                            <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-brand h-full" style="width: {{ ($stat->total / $activityStats->sum('total')) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Marketing KPI Table -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-lg font-black text-gray-900 mb-6 uppercase tracking-tight">Marketing Performance (Monthly)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="text-[10px] font-black text-gray-400 uppercase text-left border-b border-gray-100">
                                    <th class="pb-4">Marketing Name</th>
                                    <th class="pb-4 text-right">Activities</th>
                                    <th class="pb-4 text-right">Target Achievement</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($marketingKPI as $m)
                                <tr>
                                    <td class="py-4 text-sm font-bold text-gray-800">{{ $m->name }}</td>
                                    <td class="py-4 text-right text-sm font-black text-brand">{{ $m->activities_count }}</td>
                                    <td class="py-4 text-right">
                                        <span class="px-2 py-1 bg-green-50 text-green-700 text-[10px] font-black rounded-lg">ON TRACK</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
