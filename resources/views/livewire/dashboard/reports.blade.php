<div>
    {{-- Include Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-[var(--success)] bg-[var(--success-bg)] border border-[var(--border-color)] px-2 py-0.5 rounded-md">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" />
                    </svg>
                    +12.4% vs last month
                </span>
            </div>
        </div>

        {{-- Bookings --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Total Bookings</span>
                    <span class="text-2xl font-bold text-[var(--text-primary)] font-mono tracking-tight mt-1.5 inline-block">{{ $totalBookings }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--info-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--info)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-medium">
                    <span class="font-bold text-[var(--info)] font-mono">{{ $bookingsThisMonth }}</span> bookings created this month
                </span>
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-semibold font-mono">
                    {{ $totalRooms - $availableRooms }} / {{ $totalRooms }} rooms currently filled
                </span>
            </div>
        </div>

        {{-- Available Rooms --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Rooms Available</span>
                    <span class="text-2xl font-bold text-[var(--success)] font-mono tracking-tight mt-1.5 inline-block">{{ $availableRooms }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--success-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--success)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-medium">
                    Ready for walk-in registrations
                </span>
            </div>
        </div>
    </div>

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
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
                                        ticks: {
                                            color: '#64748b',
                                            font: { family: 'Outfit, sans-serif', size: 10 },
                                            precision: 0
                                        },
                                        border: { dash: [4, 4], color: 'transparent' }
                                    },
                                    x: {
                                        grid: { display: false },
                                        ticks: {
                                            color: '#64748b',
                                            font: { family: 'Outfit, sans-serif', size: 10 }
                                        },
                                        border: { color: 'transparent' }
                                    }
                                },
                                plugins: {
                                    legend: { display: false }
                                }
                            }
                        });
                    }
                }
            }" wire:ignore class="h-72 relative">
                <canvas id="bookingTrendChart"></canvas>
            </div>
        </div>

        {{-- Category Utilization --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-center justify-between mb-4 border-b border-[var(--border-color)] pb-3">
                <h3 class="text-sm font-bold text-[var(--text-primary)]">Utilization by Type</h3>
                <span class="text-[9px] text-[var(--text-muted)] font-mono tracking-wider bg-[var(--bg-secondary)] px-2.5 py-0.5 rounded border border-[var(--border-color)]">OCCUPANCY %</span>
            </div>
            <div x-data="{
                categories: {{ json_encode(array_column($categoryUtilization, 'name')) }},
                rates: {{ json_encode(array_column($categoryUtilization, 'rate')) }},
                chart: null,
                init() {
                    const ctx = document.getElementById('categoryUtilizationChart');
                    if (ctx) {
                        this.chart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: this.categories,
                                datasets: [{
                                    data: this.rates,
                                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                                    borderColor: '#2563eb',
                                    borderWidth: 1.5,
                                    borderRadius: 4,
                                    barThickness: 16
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100,
                                        grid: { color: 'rgba(0, 0, 0, 0.03)' },
                                        ticks: {
                                            color: '#64748b',
                                            font: { family: 'Outfit, sans-serif', size: 10 },
                                            callback: value => value + '%'
                                        },
                                        border: { dash: [4, 4], color: 'transparent' }
                                    },
                                    x: {
                                        grid: { display: false },
                                        ticks: {
                                            color: '#64748b',
                                            font: { family: 'Outfit, sans-serif', size: 10 }
                                        },
                                        border: { color: 'transparent' }
                                    }
                                },
                                plugins: {
                                    legend: { display: false }
                                }
                            }
                        });
                    }
                }
            }" wire:ignore class="h-72 relative">
                <canvas id="categoryUtilizationChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Tables Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Bookings Table --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div>
                <div class="flex items-center justify-between mb-5 border-b border-[var(--border-color)] pb-3">
                    <h3 class="text-sm font-bold text-[var(--text-primary)]">Recent Reservations</h3>
                    <a href="{{ route('bookings') }}" class="text-xs font-medium text-[var(--accent-primary)] hover:underline transition-colors border border-[var(--border-color)] bg-[var(--bg-secondary)] px-2.5 py-1 rounded-[var(--radius-sm)]">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-[var(--bg-secondary)] border-b border-[var(--border-color)] text-[var(--text-secondary)] font-semibold">
                                <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Room</th>
                                <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Guest</th>
                                <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Stay Dates</th>
                                <th class="p-3 text-xs font-semibold text-[var(--text-muted)] text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-color)] text-[var(--text-secondary)]">
                            @forelse($recentBookings as $booking)
                                <tr class="hover:bg-[var(--bg-card-hover)] transition-colors duration-200">
                                    <td class="p-3 font-bold text-[var(--text-primary)]">Rm {{ $booking->room->room_number }}</td>
                                    <td class="p-3 font-semibold text-[var(--text-primary)]">{{ $booking->guest->name }}</td>
                                    <td class="p-3 text-[var(--text-muted)] font-mono text-[11px]">{{ $booking->check_in_date->format('d M') }} - {{ $booking->check_out_date->format('d M Y') }}</td>
                                    <td class="p-3 text-right">
                                        @php
                                            $bColor = '';
                                            if($booking->status === 'confirmed') {
                                                $bColor = 'bg-[var(--warning-bg)] text-[var(--warning)] border border-[var(--border-color)]';
                                            } elseif($booking->status === 'checked_in') {
                                                $bColor = 'bg-[var(--info-bg)] text-[var(--info)] border border-[var(--border-color)]';
                                            } elseif($booking->status === 'checked_out') {
                                                $bColor = 'bg-[var(--success-bg)] text-[var(--success)] border border-[var(--border-color)]';
                                            } else {
                                                $bColor = 'bg-[var(--bg-secondary)] text-[var(--text-secondary)] border border-[var(--border-color)]';
                                            }
                                        @endphp
                                        <span class="inline-flex rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold {{ $bColor }}">
                                            {{ str_replace('_', ' ', $booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-[var(--text-muted)] font-medium">No recent bookings.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Recent Payment Activities --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div>
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
</div>
