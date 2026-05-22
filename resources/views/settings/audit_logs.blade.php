<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit Logs (Activity Tracking)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-widest">User</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-widest">Event</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-widest">Module</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-widest">Changes</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-widest">Time</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach (\App\Models\AuditLog::with('user')->latest()->paginate(20) as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="font-bold text-gray-900">{{ $log->user->name ?? 'System' }}</div>
                                    <div class="text-[10px] text-gray-400 font-black">{{ $log->ip_address }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase shadow-sm
                                        {{ $log->event == 'created' ? 'bg-green-50 text-green-700' : '' }}
                                        {{ $log->event == 'updated' ? 'bg-blue-50 text-blue-700' : '' }}
                                        {{ $log->event == 'deleted' ? 'bg-red-50 text-red-700' : '' }}
                                    ">
                                        {{ $log->event }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600 font-bold uppercase tracking-tighter">
                                    {{ class_basename($log->auditable_type) }}
                                </td>
                                <td class="px-6 py-4 text-xs font-medium">
                                    @if($log->event == 'updated')
                                        <div class="max-w-xs truncate text-gray-500 italic">
                                            Changed: {{ implode(', ', array_keys($log->new_values ?? [])) }}
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400 font-bold">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(\App\Models\AuditLog::count() > 20)
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ \App\Models\AuditLog::latest()->paginate(20)->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
