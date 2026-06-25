<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Booking;
use App\Models\FoodOrder;
use App\Models\HousekeepingTask;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Reports extends Component
{
    protected $listeners = ['branchChanged' => '$refresh'];

    public function render(): View
    {
        $user = auth()->user();

        // Non-admin roles each get a focused operational dashboard.
        if (! $user->isAdmin()) {
            if ($user->isHousekeeping()) {
                return $this->housekeepingDashboard();
            }
            if ($user->isFnb()) {
                return $this->fnbDashboard();
            }
            if ($user->isFrontDesk()) {
                return $this->frontDeskDashboard();
            }
        }

        return $this->adminDashboard();
    }

    /** Hotel-wide command center — admin & superadmin. Rolls up every department. */
    private function adminDashboard(): View
    {
        $branchId = session('selected_branch_id', 1);
        $today = now()->toDateString();
        $inRoomBranch = fn ($q) => $q->where('branch_id', $branchId);
        $inFnbBranch = fn ($q) => $q->whereHas('booking.room', fn ($r) => $r->where('branch_id', $branchId));

        // Financial + occupancy headline
        $totalRevenue = Payment::whereHas('booking.room', $inRoomBranch)->where('status', 'paid')->sum('amount');

        $totalRooms = Room::where('branch_id', $branchId)->count();
        $occupiedRooms = Room::where('branch_id', $branchId)->where('status', 'occupied')->count();
        $availableRooms = Room::where('branch_id', $branchId)->where('status', 'available')->count();
        $readyRooms = Room::where('branch_id', $branchId)->where('status', 'ready')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;
        $inHouse = Booking::whereHas('room', $inRoomBranch)->where('status', 'checked_in')->count();

        // Front desk pulse
        $arrivalsToday = Booking::whereHas('room', $inRoomBranch)
            ->whereDate('check_in_date', $today)->whereIn('status', ['confirmed', 'pending'])->count();
        $departuresToday = Booking::whereHas('room', $inRoomBranch)
            ->whereDate('check_out_date', $today)->where('status', 'checked_in')->count();

        // Housekeeping pulse
        $cleaningCount = Room::where('branch_id', $branchId)->where('status', 'cleaning')->count();
        $maintenanceCount = Room::where('branch_id', $branchId)->where('status', 'maintenance')->count();

        // F&B pulse
        $fnbOrdersToday = FoodOrder::where($inFnbBranch)->whereDate('created_at', $today)->count();
        $fnbRevenueToday = (float) FoodOrder::where($inFnbBranch)->whereDate('created_at', $today)
            ->where('status', 'completed')->sum('total_price');
        $fnbPending = FoodOrder::where($inFnbBranch)->whereIn('status', ['processed', 'preparing'])->count();

        // 7-Day Booking Trend
        $trendQuery = Booking::whereHas('room', $inRoomBranch)
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date_label'), DB::raw('count(*) as total'))
            ->groupBy('date_label')->pluck('total', 'date_label')->toArray();

        $bookingTrendLabels = [];
        $bookingTrendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $bookingTrendLabels[] = now()->subDays($i)->format('D, d M');
            $bookingTrendData[] = $trendQuery[$date] ?? 0;
        }

        // Recent revenue activity
        $recentPayments = Payment::with(['booking.guest'])
            ->whereHas('booking.room', $inRoomBranch)
            ->orderByDesc('created_at')->limit(6)->get();

        return view('livewire.dashboard.reports', [
            'totalRevenue' => $totalRevenue,
            'occupancyRate' => $occupancyRate,
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'readyRooms' => $readyRooms,
            'inHouse' => $inHouse,
            'arrivalsToday' => $arrivalsToday,
            'departuresToday' => $departuresToday,
            'cleaningCount' => $cleaningCount,
            'maintenanceCount' => $maintenanceCount,
            'fnbOrdersToday' => $fnbOrdersToday,
            'fnbRevenueToday' => $fnbRevenueToday,
            'fnbPending' => $fnbPending,
            'bookingTrendLabels' => $bookingTrendLabels,
            'bookingTrendData' => $bookingTrendData,
            'recentPayments' => $recentPayments,
        ]);
    }

    /** Front desk: arrivals, departures, in-house, occupancy. */
    private function frontDeskDashboard(): View
    {
        $branchId = session('selected_branch_id', 1);
        $today = now()->toDateString();
        $inBranch = fn ($q) => $q->where('branch_id', $branchId);

        $arrivalsToday = Booking::whereHas('room', $inBranch)
            ->whereDate('check_in_date', $today)
            ->whereIn('status', ['confirmed', 'pending'])->count();

        $departuresToday = Booking::whereHas('room', $inBranch)
            ->whereDate('check_out_date', $today)
            ->where('status', 'checked_in')->count();

        $inHouse = Booking::whereHas('room', $inBranch)->where('status', 'checked_in')->count();

        $totalRooms = Room::where('branch_id', $branchId)->count();
        $availableRooms = Room::where('branch_id', $branchId)->where('status', 'available')->count();
        $readyRooms = Room::where('branch_id', $branchId)->where('status', 'ready')->count();
        $occupiedRooms = Room::where('branch_id', $branchId)->where('status', 'occupied')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        $arrivals = Booking::with(['guest', 'room'])
            ->whereHas('room', $inBranch)
            ->whereDate('check_in_date', $today)
            ->whereIn('status', ['confirmed', 'pending'])
            ->orderBy('check_in_date')->get();

        $departures = Booking::with(['guest', 'room'])
            ->whereHas('room', $inBranch)
            ->whereDate('check_out_date', $today)
            ->where('status', 'checked_in')
            ->orderBy('check_out_date')->get();

        return view('livewire.dashboard.front-desk', [
            'arrivalsToday' => $arrivalsToday,
            'departuresToday' => $departuresToday,
            'inHouse' => $inHouse,
            'availableRooms' => $availableRooms,
            'readyRooms' => $readyRooms,
            'occupancyRate' => $occupancyRate,
            'arrivals' => $arrivals,
            'departures' => $departures,
        ]);
    }

    /** Housekeeping: cleaning queue, maintenance, ready, task breakdown. */
    private function housekeepingDashboard(): View
    {
        $branchId = session('selected_branch_id', 1);
        $today = now()->toDateString();
        $inBranch = fn ($q) => $q->where('branch_id', $branchId);

        $cleaningCount = Room::where('branch_id', $branchId)->where('status', 'cleaning')->count();
        $maintenanceCount = Room::where('branch_id', $branchId)->where('status', 'maintenance')->count();
        $readyCount = Room::where('branch_id', $branchId)->where('status', 'ready')->count();

        $tasksByStatus = HousekeepingTask::whereHas('room', $inBranch)
            ->whereDate('schedule_date', $today)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->pluck('total', 'status')->toArray();

        $openTasks = HousekeepingTask::with(['room.roomType', 'staff'])
            ->whereHas('room', $inBranch)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->orderBy('schedule_date')->get();

        return view('livewire.dashboard.housekeeping-overview', [
            'cleaningCount' => $cleaningCount,
            'maintenanceCount' => $maintenanceCount,
            'readyCount' => $readyCount,
            'scheduledCount' => $tasksByStatus['scheduled'] ?? 0,
            'inProgressCount' => $tasksByStatus['in_progress'] ?? 0,
            'completedCount' => $tasksByStatus['completed'] ?? 0,
            'openTasks' => $openTasks,
        ]);
    }

    /** F&B: today's orders, revenue, pending queue, menu size. */
    private function fnbDashboard(): View
    {
        $branchId = session('selected_branch_id', 1);
        $today = now()->toDateString();
        $inBranch = fn ($q) => $q->whereHas('booking.room', fn ($r) => $r->where('branch_id', $branchId));

        $ordersToday = FoodOrder::where($inBranch)->whereDate('created_at', $today)->count();
        $revenueToday = (float) FoodOrder::where($inBranch)->whereDate('created_at', $today)
            ->where('status', 'completed')->sum('total_price');
        $pendingOrders = FoodOrder::where($inBranch)->whereIn('status', ['processed', 'preparing'])->count();
        $menuItems = Service::where('type', 'f_and_b')->count();

        $recentOrders = FoodOrder::with(['booking.guest', 'booking.room', 'items'])
            ->where($inBranch)
            ->orderByDesc('created_at')->limit(8)->get();

        // 7-day F&B revenue trend
        $trend = FoodOrder::where($inBranch)
            ->where('created_at', '>=', now()->subDays(7))
            ->where('status', 'completed')
            ->select(DB::raw('DATE(created_at) as date_label'), DB::raw('sum(total_price) as total'))
            ->groupBy('date_label')->pluck('total', 'date_label')->toArray();

        $revenueTrendLabels = [];
        $revenueTrendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenueTrendLabels[] = now()->subDays($i)->format('D, d M');
            $revenueTrendData[] = (float) ($trend[$date] ?? 0);
        }

        return view('livewire.dashboard.fnb-overview', [
            'ordersToday' => $ordersToday,
            'revenueToday' => $revenueToday,
            'pendingOrders' => $pendingOrders,
            'menuItems' => $menuItems,
            'recentOrders' => $recentOrders,
            'revenueTrendLabels' => $revenueTrendLabels,
            'revenueTrendData' => $revenueTrendData,
        ]);
    }
}
