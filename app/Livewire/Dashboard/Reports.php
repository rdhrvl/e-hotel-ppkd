<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Reports & Analytics')]
class Reports extends Component
{
    protected $listeners = ['branchChanged' => '$refresh'];

    public function render(): \Illuminate\Contracts\View\View
    {
        $branchId = session('selected_branch_id', 1);

        // Revenue summary
        $totalRevenue = Payment::whereHas('booking.room', function ($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })->where('status', 'paid')->sum('amount');

        // Total bookings
        $totalBookings = Booking::whereHas('room', function ($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })->count();

        // Total bookings this month
        $bookingsThisMonth = Booking::whereHas('room', function ($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })->whereMonth('created_at', now()->month)->count();

        // Occupancy calculation
        $totalRooms = Room::where('branch_id', $branchId)->count();
        $occupiedRooms = Room::where('branch_id', $branchId)->where('status', 'occupied')->count();
        $availableRooms = Room::where('branch_id', $branchId)->where('status', 'available')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        // Recent Bookings
        $recentBookings = Booking::with(['guest', 'room'])
            ->whereHas('room', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent Payments
        $recentPayments = Payment::with(['booking.guest'])
            ->whereHas('booking.room', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 7-Day Booking Trend
        $trendQuery = Booking::whereHas('room', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date_label'), DB::raw('count(*) as total'))
            ->groupBy('date_label')
            ->get()
            ->pluck('total', 'date_label')
            ->toArray();

        $bookingTrendLabels = [];
        $bookingTrendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $bookingTrendLabels[] = now()->subDays($i)->format('D, d M');
            $bookingTrendData[] = $trendQuery[$date] ?? 0;
        }

        // Category utilization
        $categoryUtilization = Room::with('roomType')
            ->where('branch_id', $branchId)
            ->select('room_type_id', DB::raw('count(*) as total'), DB::raw('sum(case when status = "occupied" then 1 else 0 end) as occupied'))
            ->groupBy('room_type_id')
            ->get()
            ->map(fn($item) => [
                'name' => $item->roomType->name,
                'occupied' => (int) $item->occupied,
                'total' => (int) $item->total,
                'rate' => $item->total > 0 ? round(($item->occupied / $item->total) * 100, 1) : 0,
            ])
            ->toArray();

        return view('livewire.dashboard.reports', [
            'totalRevenue' => $totalRevenue,
            'totalBookings' => $totalBookings,
            'bookingsThisMonth' => $bookingsThisMonth,
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'occupancyRate' => $occupancyRate,
            'recentBookings' => $recentBookings,
            'recentPayments' => $recentPayments,
            'bookingTrendLabels' => $bookingTrendLabels,
            'bookingTrendData' => $bookingTrendData,
            'categoryUtilization' => $categoryUtilization,
        ]);
    }
}
