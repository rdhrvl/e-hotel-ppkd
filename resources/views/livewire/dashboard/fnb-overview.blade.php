<div>
    {{-- Include Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Orders Today --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Orders Today</span>
                    <span class="text-2xl font-bold text-[var(--info)] font-mono tracking-tight mt-1.5 inline-block">{{ $ordersToday }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--info-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--info)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-medium">Placed today</span>
            </div>
        </div>

        {{-- Revenue Today --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Revenue Today</span>
                    <span class="text-2xl font-bold text-[var(--success)] font-mono tracking-tight mt-1.5 inline-block">Rp {{ number_format($revenueToday) }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--success-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--success)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0c1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2-.658C8.828 10.463 8.828 9.039 10 8.16c1.172-.879 3.07-.879 4.242 0l.879.659" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-medium">Completed orders</span>
            </div>
        </div>

        {{-- Pending Orders --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Pending Orders</span>
                    <span class="text-2xl font-bold text-[var(--warning)] font-mono tracking-tight mt-1.5 inline-block">{{ $pendingOrders }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--warning-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--warning)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-medium">Processing or preparing</span>
            </div>
        </div>

        {{-- Menu Items --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Menu Items</span>
                    <span class="text-2xl font-bold text-[var(--text-primary)] font-mono tracking-tight mt-1.5 inline-block">{{ $menuItems }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--bg-secondary)] border border-[var(--border-color)] flex items-center justify-center text-[var(--text-primary)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-medium">Available on menu</span>
            </div>
        </div>
    </div>

    {{-- Revenue Trend + Recent Orders --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Revenue Trend --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-center justify-between mb-4 border-b border-[var(--border-color)] pb-3">
                <h3 class="text-sm font-bold text-[var(--text-primary)]">F&amp;B Revenue (Last 7 Days)</h3>
                <span class="text-[9px] text-[var(--text-muted)] font-mono tracking-wider bg-[var(--bg-secondary)] px-2.5 py-0.5 rounded border border-[var(--border-color)]">LINE TREND</span>
            </div>
            <div x-data="{
                labels: {{ json_encode($revenueTrendLabels) }},
                data: {{ json_encode($revenueTrendData) }},
                chart: null,
                init() {
                    const ctx = document.getElementById('fnbRevenueChart');
                    if (ctx) {
                        this.chart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: this.labels,
                                datasets: [{
                                    label: 'Revenue',
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
                                        ticks: { color: '#64748b', font: { family: 'Outfit, sans-serif', size: 10 } },
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
                <canvas id="fnbRevenueChart"></canvas>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-center justify-between mb-5 border-b border-[var(--border-color)] pb-3">
                <h3 class="text-sm font-bold text-[var(--text-primary)]">Recent Orders</h3>
                <a href="{{ route('fnb') }}" class="text-xs font-medium text-[var(--accent-primary)] hover:underline border border-[var(--border-color)] bg-[var(--bg-secondary)] px-2.5 py-1 rounded-[var(--radius-sm)]">Go to F&amp;B</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[var(--bg-secondary)] border-b border-[var(--border-color)]">
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Room</th>
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Items</th>
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Status</th>
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)] text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border-color)] text-[var(--text-secondary)]">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-[var(--bg-card-hover)] transition-colors duration-200">
                                <td class="p-3 font-bold text-[var(--text-primary)]">{{ $order->booking->room->room_number ?? 'N/A' }}</td>
                                <td class="p-3 text-[var(--text-muted)]">{{ $order->items->count() }} item(s)</td>
                                <td class="p-3">
                                    @php $oColor = $order->status === 'completed' ? 'bg-[var(--success-bg)] text-[var(--success)]' : ($order->status === 'delivered' ? 'bg-[var(--info-bg)] text-[var(--info)]' : 'bg-[var(--warning-bg)] text-[var(--warning)]'); @endphp
                                    <span class="inline-flex rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold border border-[var(--border-color)] {{ $oColor }}">{{ str_replace('_', ' ', $order->status) }}</span>
                                </td>
                                <td class="p-3 text-right font-bold text-[var(--success)] font-mono text-[13px]">Rp {{ number_format((float) $order->total_price) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="p-8 text-center text-[var(--text-muted)] font-medium">No orders yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
