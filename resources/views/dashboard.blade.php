<x-app-layout>
    @section('title', 'Dashboard Monitoring')

    <div class="space-y-8">
        <!-- QUICK ACTIONS SECTION -->
        <div class="bg-brand/5 border border-brand/10 p-6 rounded-3xl">
            <h3 class="text-xs font-black text-brand uppercase tracking-widest mb-4 ml-1">Quick Actions</h3>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('leads.create') }}" class="flex items-center gap-3 bg-white px-6 py-3 rounded-2xl shadow-sm border border-brand/10 hover:border-brand transition-all group">
                    <div class="w-10 h-10 bg-brand/10 rounded-xl flex items-center justify-center text-brand group-hover:bg-brand group-hover:text-white transition-all"><i class="fa-solid fa-user-plus"></i></div>
                    <span class="text-sm font-bold text-gray-700">Tambah Lead</span>
                </a>
                <a href="{{ route('customers.create') }}" class="flex items-center gap-3 bg-white px-6 py-3 rounded-2xl shadow-sm border border-brand/10 hover:border-brand transition-all group">
                    <div class="w-10 h-10 bg-brand/10 rounded-xl flex items-center justify-center text-brand group-hover:bg-brand group-hover:text-white transition-all"><i class="fa-solid fa-building-circle-check"></i></div>
                    <span class="text-sm font-bold text-gray-700">Tambah Customer</span>
                </a>
                <a href="{{ route('activities.create') }}" class="flex items-center gap-3 bg-white px-6 py-3 rounded-2xl shadow-sm border border-brand/10 hover:border-brand transition-all group">
                    <div class="w-10 h-10 bg-brand/10 rounded-xl flex items-center justify-center text-brand group-hover:bg-brand group-hover:text-white transition-all"><i class="fa-solid fa-calendar-plus"></i></div>
                    <span class="text-sm font-bold text-gray-700">Tambah Aktivitas</span>
                </a>
            </div>
        </div>

        <!-- KPI CARDS SECTION -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Lead</p>
                <p class="text-3xl font-black text-gray-900 mt-1">{{ $stats['total_lead'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Customer</p>
                <p class="text-3xl font-black text-gray-900 mt-1">{{ $stats['total_customer'] }}</p>
            </div>
            <div class="bg-brand p-6 rounded-3xl shadow-lg text-white shadow-brand/20">
                <p class="text-[10px] font-black text-white/60 uppercase tracking-widest">Follow Up Hari Ini</p>
                <p class="text-3xl font-black mt-1">{{ $stats['followup_today'] }}</p>
            </div>
            <div class="bg-red-500 p-6 rounded-3xl shadow-lg text-white">
                <p class="text-[10px] font-black text-white/60 uppercase tracking-widest">FU Terlambat</p>
                <p class="text-3xl font-black mt-1">{{ $stats['followup_overdue'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Customer Aktif</p>
                <p class="text-3xl font-black text-green-600 mt-1">{{ $stats['customer_active'] }}</p>
            </div>
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Aktivitas Hari Ini</p>
                <p class="text-3xl font-black text-brand mt-1">{{ $stats['activity_today'] }}</p>
            </div>
        </div>

        <!-- WIDGETS SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Lead Conversion Chart -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center">
                    <i class="fa-solid fa-chart-line mr-3 text-brand"></i> Lead Growth (Last 6 Months)
                </h3>
                <div class="h-[250px]">
                    <canvas id="conversionChart"></canvas>
                </div>
            </div>

            <!-- Activity Timeline Widget -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-lg font-black text-gray-900 mb-6 flex items-center">
                    <i class="fa-solid fa-clock-rotate-left mr-3 text-brand"></i> Aktivitas Terbaru
                </h3>
                <div class="space-y-6">
                    @forelse($recentActivities as $act)
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center shrink-0 text-brand">
                            <i class="fa-solid {{ in_array($act->activity_type, ['CALL', 'WHATSAPP']) ? 'fa-phone' : 'fa-comment-dots' }} text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $act->activitable->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 line-clamp-1 italic">"{{ $act->result }}"</p>
                        </div>
                        <div class="text-right whitespace-nowrap">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $act->created_at->diffForHumans() }}</p>
                            <p class="text-[10px] font-black text-brand uppercase">{{ $act->user->name }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center py-10 text-gray-400 italic">Belum ada aktivitas.</p>
                    @endforelse
                </div>
                <div class="mt-8 pt-6 border-t border-gray-50 text-center">
                    <a href="{{ route('activities.index') }}" class="text-xs font-black text-brand uppercase tracking-widest hover:underline">Lihat Semua Aktivitas</a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('conversionChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartData['labels']) !!},
                        datasets: [{
                            label: 'Customer Growth',
                            data: {!! json_encode($chartData['data']) !!},
                            borderColor: 'rgb(12 192 223)',
                            backgroundColor: 'rgba(12, 192, 223, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: 'white',
                            pointBorderColor: 'rgb(12 192 223)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { display: false }, ticks: { precision: 0 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
