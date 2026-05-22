<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notification Logs') }}
            </h2>
            <a href="{{ route('settings.index') }}" class="text-sm text-brand hover:underline">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to Settings
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Channel</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($logs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                    <span class="px-2 py-1 rounded {{ $log->channel == 'whatsapp' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ strtoupper($log->channel) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-medium">
                                    {{ $log->recipient }}
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500">
                                    <div class="truncate max-w-xs">{{ $log->message }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs">
                                    @if($log->status == 'sent')
                                        <span class="text-green-600 font-bold"><i class="fa-solid fa-check-circle mr-1"></i> SENT</span>
                                    @elseif($log->status == 'failed')
                                        <span class="text-red-600 font-bold" title="{{ $log->error_message }}"><i class="fa-solid fa-times-circle mr-1"></i> FAILED</span>
                                    @else
                                        <span class="text-amber-600 font-bold">PENDING</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">No notification logs found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
