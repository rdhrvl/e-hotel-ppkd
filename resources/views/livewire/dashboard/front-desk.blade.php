<div>
    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Arrivals Today --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Arrivals Today</span>
                    <span class="text-2xl font-bold text-[var(--info)] font-mono tracking-tight mt-1.5 inline-block">{{ $arrivalsToday }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--info-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--info)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-medium">Guests expected to check in</span>
            </div>
        </div>

        {{-- Departures Today --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 flex flex-col justify-between transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-start justify-between w-full">
                <div>
                    <span class="text-xs font-semibold text-[var(--text-secondary)] block">Departures Today</span>
                    <span class="text-2xl font-bold text-[var(--warning)] font-mono tracking-tight mt-1.5 inline-block">{{ $departuresToday }}</span>
                </div>
                <div class="h-9 w-9 rounded-md bg-[var(--warning-bg)] border border-[var(--border-color)] flex items-center justify-center text-[var(--warning)]">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0110.5 3h6a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0116.5 21h-6a2.25 2.25 0 01-2.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <span class="text-xs text-[var(--text-secondary)] font-medium">Checkouts due today</span>
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
                <span class="text-xs text-[var(--text-secondary)] font-medium">Currently checked in</span>
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
    </div>

    {{-- Arrivals / Departures Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Arrivals --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-center justify-between mb-5 border-b border-[var(--border-color)] pb-3">
                <h3 class="text-sm font-bold text-[var(--text-primary)]">Today's Arrivals</h3>
                <a href="{{ route('bookings') }}" class="text-xs font-medium text-[var(--accent-primary)] hover:underline border border-[var(--border-color)] bg-[var(--bg-secondary)] px-2.5 py-1 rounded-[var(--radius-sm)]">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[var(--bg-secondary)] border-b border-[var(--border-color)]">
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Room</th>
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Guest</th>
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)] text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border-color)] text-[var(--text-secondary)]">
                        @forelse($arrivals as $booking)
                            <tr class="hover:bg-[var(--bg-card-hover)] transition-colors duration-200">
                                <td class="p-3 font-bold text-[var(--text-primary)]">Rm {{ $booking->room->room_number }}</td>
                                <td class="p-3 font-semibold text-[var(--text-primary)]">{{ $booking->guest->name }}</td>
                                <td class="p-3 text-right">
                                    <span class="inline-flex rounded-[var(--radius-sm)] px-2.5 py-0.5 text-xs font-semibold bg-[var(--warning-bg)] text-[var(--warning)] border border-[var(--border-color)]">{{ str_replace('_', ' ', $booking->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="p-8 text-center text-[var(--text-muted)] font-medium">No arrivals scheduled today.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Departures --}}
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-6 transition-all duration-200 hover:border-[var(--border-hover)] hover:shadow-[var(--shadow-lg)]">
            <div class="flex items-center justify-between mb-5 border-b border-[var(--border-color)] pb-3">
                <h3 class="text-sm font-bold text-[var(--text-primary)]">Today's Departures</h3>
                <a href="{{ route('bookings') }}" class="text-xs font-medium text-[var(--accent-primary)] hover:underline border border-[var(--border-color)] bg-[var(--bg-secondary)] px-2.5 py-1 rounded-[var(--radius-sm)]">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[var(--bg-secondary)] border-b border-[var(--border-color)]">
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Room</th>
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)]">Guest</th>
                            <th class="p-3 text-xs font-semibold text-[var(--text-muted)] text-right">Check-out</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border-color)] text-[var(--text-secondary)]">
                        @forelse($departures as $booking)
                            <tr class="hover:bg-[var(--bg-card-hover)] transition-colors duration-200">
                                <td class="p-3 font-bold text-[var(--text-primary)]">Rm {{ $booking->room->room_number }}</td>
                                <td class="p-3 font-semibold text-[var(--text-primary)]">{{ $booking->guest->name }}</td>
                                <td class="p-3 text-right text-[var(--text-muted)] font-mono text-[11px]">{{ $booking->check_out_date->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="p-8 text-center text-[var(--text-muted)] font-medium">No departures due today.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
