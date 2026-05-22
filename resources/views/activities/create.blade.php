<x-app-layout>
    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control {
            border-radius: 0.75rem !important;
            padding: 0.75rem 1rem !important;
            background-color: rgb(249 250 251) !important;
            border-color: rgb(229 231 235) !important;
            font-size: 1rem !important;
            line-height: 1.5rem !important;
        }
        .ts-wrapper.focus .ts-control {
            box-shadow: 0 0 0 2px rgba(12, 192, 223, 0.2) !important;
            border-color: rgb(12, 192, 223) !important;
        }
        .ts-dropdown {
            border-radius: 0.75rem !important;
            margin-top: 0.5rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid rgb(229 231 235) !important;
        }
    </style>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log New Activity') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border border-gray-100">
                <form method="POST" action="{{ route('activities.store') }}" class="space-y-6">
                    @csrf

                    <!-- Target Selection -->
                    <div class="space-y-6 pb-8 border-b border-gray-100 mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-1/3">
                                <label class="block text-sm font-bold text-gray-700 ml-1">Target Type</label>
                                <select name="activitable_type" id="activitable_type" required class="block w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                    <option value="App\Models\Lead" {{ $targetType == 'Lead' ? 'selected' : '' }}>Lead / Prospek</option>
                                    <option value="App\Models\Customer" {{ $targetType == 'Customer' ? 'selected' : '' }}>Customer Master</option>
                                </select>
                            </div>
                            <div class="text-xs text-gray-500 mt-5">
                                * Selection below will filter based on this type.
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Select Leads / Target</label>
                            <select name="activitable_id" id="activitable_id" required class="block w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all text-base font-medium">
                                <option value="">-- Choose Target (Lead/Customer) --</option>
                                @foreach($leads as $l)
                                    <option value="{{ $l->id }}" data-type="App\Models\Lead" {{ ($targetType == 'Lead' || !$targetType) && $targetId == $l->id ? 'selected' : '' }}>[LEAD] {{ $l->name }}</option>
                                @endforeach
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" data-type="App\Models\Customer" {{ $targetType == 'Customer' && $targetId == $c->id ? 'selected' : '' }}>[CUST] {{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Activity Type</label>
                            <select name="activity_type" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                <option value="CALL">Call</option>
                                <option value="WHATSAPP">WhatsApp</option>
                                <option value="EMAIL">Email</option>
                                <option value="MEETING">Meeting</option>
                                <option value="VISIT">Site Visit</option>
                                <option value="DEMO">Product Demo</option>
                                <option value="NOTE">Internal Note</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Current Status</label>
                            <select name="status" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                                <option value="OPEN">Open / Pending</option>
                                <option value="DONE">Done / Completed</option>
                                <option value="MISSED">Missed</option>
                                <option value="CANCELLED">Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-gray-700 ml-1">Summary / Discussion Result</label>
                        <textarea name="result" rows="4" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white transition-all sm:text-sm" placeholder="What was discussed?"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Outcome</label>
                            <input type="text" name="outcome" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm" placeholder="e.g., Interested, Sent Proposal">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-bold text-gray-700 ml-1">Next Follow Up Date</label>
                            <input type="datetime-local" name="next_followup_at" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand transition-all sm:text-sm">
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end gap-3">
                        <a href="{{ route('activities.index') }}" class="px-6 py-3 bg-white border border-gray-300 rounded-xl font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition-all">Cancel</a>
                        <x-primary-button class="bg-brand px-10 py-3 shadow-lg shadow-brand/20">
                            Save Activity Log
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = new TomSelect('#activitable_id', {
                create: false,
                sortField: { field: "text", direction: "asc" },
                maxOptions: null,
                placeholder: "-- Choose Target --",
            });

            const typeSelect = document.getElementById('activitable_type');
            
            // Initial options from the HTML
            const initialOptions = Array.from(document.querySelectorAll('#activitable_id option'))
                .filter(opt => opt.value !== "")
                .map(opt => ({
                    value: opt.value,
                    text: opt.text,
                    type: opt.getAttribute('data-type')
                }));

            function filterOptions() {
                const selectedType = typeSelect.value;
                const currentValue = select.getValue();
                
                select.clearOptions();
                
                const filtered = initialOptions.filter(opt => opt.type === selectedType);
                select.addOptions(filtered);
                
                // Set value if it's in the filtered list
                if (currentValue && filtered.some(f => f.value === currentValue)) {
                    select.setValue(currentValue);
                } else {
                    select.setValue('');
                }
                
                select.refreshOptions(false);
            }

            typeSelect.addEventListener('change', filterOptions);
            
            // Run initial filter
            filterOptions();

            // Special case for pre-selected target from request
            @if($targetId)
                setTimeout(() => {
                    select.setValue('{{ $targetId }}');
                }, 100);
            @endif
        });
    </script>
    @endpush
</x-app-layout>