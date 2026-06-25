<div>
    {{-- Include Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Headline KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- Revenue --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Total Revenue</span>
                    <span class="text-2xl font-bold text-[var(--success)] font-mono tracking-tight mt-1.5 inline-block">Rp {{ number_format($totalRevenue) }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--success-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--success)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0c1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2-.658C8.828 10.463 8.828 9.039 10 8.16c1.172-.879 3.07-.879 4.242 0l.879.659" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-medium">All paid transactions</span>
            </div>
        </div>

        {{-- Occupancy --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Occupancy Rate</span>
                    <span class="text-2xl font-bold text-[var(--info)] font-mono tracking-tight mt-1.5 inline-block">{{ $occupancyRate }}%</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--info-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--info)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-semibold font-mono">{{ $readyRooms }} ready · {{ $availableRooms }} available</span>
            </div>
        </div>

        {{-- In-House --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">In-House Guests</span>
                    <span class="text-2xl font-bold text-[var(--text-primary)] font-mono tracking-tight mt-1.5 inline-block">{{ $inHouse }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--bg-secondary)] border border-[var(--border-color)] flex items-center justify-center text-[var(--text-primary)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 20M3 11.625a3 3 0 116 0 3 3 0 01-6 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-semibold font-mono">{{ $arrivalsToday }} arriving · {{ $departuresToday }} leaving today</span>
            </div>
        </div>

        {{-- F&B Revenue Today --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">F&amp;B Revenue Today</span>
                    <span class="text-2xl font-bold text-[var(--success)] font-mono tracking-tight mt-1.5 inline-block">Rp {{ number_format($fnbRevenueToday) }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--success-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--success)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-semibold font-mono">{{ $fnbOrdersToday }} orders · {{ $fnbPending }} pending</span>
            </div>
        </div>
    </div>

    {{-- Department Pulse --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        {{-- Front Desk --}}
        <a href="{{ route('bookings') }}" class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-5 transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-center justify-between mb-3 border-b border-[var(--border-color)] pb-2">
                <h3 class="text-sm font-bold text-[var(--text-primary)]">Front Desk</h3>
                <span class="text-[9px] text-[var(--text-muted)] font-mono tracking-wider bg-[var(--bg-secondary)] px-2 py-0.5 rounded border border-[var(--border-color)]">TODAY</span>
            </div>
            <div class="flex justify-between text-center">
                <div class="flex-1">
                    <span class="block text-xl font-bold text-[var(--info)] font-mono">{{ $arrivalsToday }}</span>
                    <span class="text-[11px] text-[var(--text-secondary)] font-medium">Arrivals</span>
                </div>
                <div class="flex-1 border-l border-[var(--border-color)]">
                    <span class="block text-xl font-bold text-[var(--warning)] font-mono">{{ $departuresToday }}</span>
                    <span class="text-[11px] text-[var(--text-secondary)] font-medium">Departures</span>
                </div>
            </div>
        </a>

        {{-- Housekeeping --}}
        <a href="{{ route('housekeeping') }}" class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-5 transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-center justify-between mb-3 border-b border-[var(--border-color)] pb-2">
                <h3 class="text-sm font-bold text-[var(--text-primary)]">Housekeeping</h3>
                <span class="text-[9px] text-[var(--text-muted)] font-mono tracking-wider bg-[var(--bg-secondary)] px-2 py-0.5 rounded border border-[var(--border-color)]">ROOMS</span>
            </div>
            <div class="flex justify-between text-center">
                <div class="flex-1">
                    <span class="block text-xl font-bold text-[var(--info)] font-mono">{{ $cleaningCount }}</span>
                    <span class="text-[11px] text-[var(--text-secondary)] font-medium">Cleaning</span>
                </div>
                <div class="flex-1 border-l border-[var(--border-color)]">
                    <span class="block text-xl font-bold text-[var(--danger)] font-mono">{{ $maintenanceCount }}</span>
                    <span class="text-[11px] text-[var(--text-secondary)] font-medium">Maintenance</span>
                </div>
            </div>
        </a>

        {{-- Food & Beverage --}}
        <a href="{{ route('fnb') }}" class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-5 transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-center justify-between mb-3 border-b border-[var(--border-color)] pb-2">
                <h3 class="text-sm font-bold text-[var(--text-primary)]">Food &amp; Beverage</h3>
                <span class="text-[9px] text-[var(--text-muted)] font-mono tracking-wider bg-[var(--bg-secondary)] px-2 py-0.5 rounded border border-[var(--border-color)]">TODAY</span>
            </div>
            <div class="flex justify-between text-center">
                <div class="flex-1">
                    <span class="block text-xl font-bold text-[var(--text-primary)] font-mono">{{ $fnbOrdersToday }}</span>
                    <span class="text-[11px] text-[var(--text-secondary)] font-medium">Orders</span>
                </div>
                <div class="flex-1 border-l border-[var(--border-color)]">
                    <span class="block text-xl font-bold text-[var(--warning)] font-mono">{{ $fnbPending }}</span>
                    <span class="text-[11px] text-[var(--text-secondary)] font-medium">Pending</span>
                </div>
            </div>
        </a>
    </div>

    {{-- Trend + Recent Revenue --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Booking Trend --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-center justify-between mb-4 border-b border-[var(--border-color)] pb-3">
                <h3 class="text-sm font-bold text-[var(--text-primary)]">Booking Trends (Last 7 Days)</h3>
                <span class="text-[9px] text-[var(--text-muted)] font-mono tracking-wider bg-[var(--bg-secondary)] px-2.5 py-0.5 rounded border border-[var(--border-color)]">LINE TREND</span>
            </div>
            <div x-data="{
                labels: {{ json_encode($bookingTrendLabels) }},
                data: {{ json_encode($bookingTrendData) }},
                chart: null,
                init() {
                    const ctx = document.getElementById('bookingTrendChart');
                    if (ctx) {
                        this.chart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: this.labels,
                                datasets: [{
                                    label: 'Bookings Created',
                                    data: this.data,
                                    borderColor: '#2563eb',
                                    backgroundColor: 'rgba(37, 99, 235, 0.04)',
                                    borderWidth: 2,
                                    tension: 0.25,
                                    fill: true,
                                    pointBackgroundColor: '#2563eb',
                                    pointBorderColor: '#ffffff',
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    pointBorderWidth: 1.5
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: 'rgba(0, 0, 0, 0.03)' },
                                        ticks: { color: '#64748b', font: { family: 'Outfit, sans-serif', size: 10 }, precision: 0 },
                                        border: { dash: [4, 4], color: 'transparent' }
                                    },
                                    x: {
                                        grid: { display: false },
                                        ticks: { color: '#64748b', font: { family: 'Outfit, sans-serif', size: 10 } },
                                        border: { color: 'transparent' }
                                    }
                                },
                                plugins: { legend: { display: false } }
                            }
                        });
                    }
                }
            }" wire:ignore class="h-72 relative">
                <canvas id="bookingTrendChart"></canvas>
            </div>
        </div>

        {{-- Recent Revenue Activities --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-center justify-between mb-5 border-b border-[var(--border-color)] pb-3">
                <h3 class="text-sm font-bold text-[var(--text-primary)]">Recent Revenue Activities</h3>
                <a href="{{ route('payments') }}" class="text-xs font-medium text-[var(--accent-primary)] hover:underline transition-colors border border-[var(--border-color)] bg-[var(--bg-secondary)] px-2.5 py-1 rounded-[var(--radius-sm)]">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[var(--bg-secondary)] border-b border-[var(--border-color)] text-[var(--text-secondary)] font-semibold">
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Time</th>
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Guest</th>
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Method</th>
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)] text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border-color)] text-[var(--text-secondary)]">
                        @forelse($recentPayments as $payment)
                            <tr class="hover:bg-[var(--bg-card-hover)] transition-colors duration-200">
                                <td class="p-3 font-mono text-[var(--text-muted)] text-[11px]">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                <td class="p-3 font-bold text-[var(--text-primary)]">{{ $payment->booking->guest->name ?? 'N/A' }}</td>
                                <td class="p-3">
                                    <span class="inline-flex rounded-md bg-[var(--bg-secondary)] px-2 py-0.5 text-[9px] font-semibold text-[var(--text-secondary)] border border-[var(--border-color)] tracking-wide uppercase">{{ str_replace('_', ' ', $payment->method) }}</span>
                                </td>
                                <td class="p-3 text-right font-bold text-[var(--success)] font-mono text-[13px]">Rp {{ number_format((float)$payment->amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-[var(--text-muted)] font-medium">No recent transactions.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
