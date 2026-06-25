<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\HousekeepingTask;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Housekeeping Dashboard')]
class Housekeeping extends Component
{
    use WithPagination;

    public string $filterStatus = '';
    
    // Add/Assign task form states
    public ?int $selectedRoomId = null;
    public ?int $selectedStaffId = null;
    public string $scheduleDate = '';

    // Room Detail Modal states
    public bool $showRoomDetailModal = false;
    public ?int $detailRoomId = null;
    public string $cleaningNotes = '';
    
    // Integrated issue report states inside details modal
    public bool $hasIssue = false;
    public string $issueType = 'missing_item';
    public string $issueDescription = '';

    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    protected $listeners = ['branchChanged' => '$refresh'];

    public function mount()
    {
        $this->scheduleDate = Carbon::now()->toDateString();

        $openRoomId = request()->query('open_room_id');
        if ($openRoomId) {
            $this->openRoomDetailModal((int) $openRoomId);
        }
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $branchId = session('selected_branch_id', 1);

        // Base query scoped to branch + optional status filter
        $baseQuery = HousekeepingTask::with(['room.roomType', 'room.currentBooking.guest', 'staff'])
            ->whereHas('room', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy($this->sortField, $this->sortDirection);

        // Paginated full list (for pagination controls)
        $tasks = (clone $baseQuery)->paginate(10);

        if ($this->getPage() > $tasks->lastPage()) {
            $this->setPage(max(1, $tasks->lastPage()));
            $tasks = (clone $baseQuery)->paginate(10);
        }

        // Pre Check-In tasks: room status = 'reserved' or 'cleaning' with a confirmed booking
        $preCheckInTasks = HousekeepingTask::with(['room.roomType', 'room.currentBooking.guest', 'staff'])
            ->whereHas('room', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId)
                  ->whereHas('bookings', fn($bq) => $bq->where('status', 'confirmed'));
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        // Pre Check-Out tasks: room status = 'occupied' (checked-in guests)
        $preCheckOutTasks = HousekeepingTask::with(['room.roomType', 'room.activeBooking.guest', 'staff'])
            ->whereHas('room', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId)
                  ->where('status', 'occupied');
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        // Get IDs already in pre check-in or pre check-out to exclude from general
        $excludeIds = $preCheckInTasks->pluck('id')->merge($preCheckOutTasks->pluck('id'))->unique();

        // General tasks: everything else
        $generalTasks = HousekeepingTask::with(['room.roomType', 'staff'])
            ->whereHas('room', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->whereNotIn('id', $excludeIds)
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        // Housekeeping staff
        $housekeepingStaff = User::whereHas('role', function ($q) {
            $q->where('slug', 'housekeeping');
        })->get();

        // Detailed room for details popup modal
        $detailRoom = $this->detailRoomId ? Room::with(['roomType', 'activeBooking.guest', 'currentBooking.guest', 'housekeepingTasks' => function ($q) {
            $q->latest();
        }])->find($this->detailRoomId) : null;

        return view('livewire.dashboard.housekeeping', [
            'tasks'           => $tasks,
            'preCheckInTasks' => $preCheckInTasks,
            'preCheckOutTasks'=> $preCheckOutTasks,
            'generalTasks'    => $generalTasks,
            'housekeepingStaff' => $housekeepingStaff,
            'detailRoom'      => $detailRoom,
        ]);
    }

    public function updateTaskStatus(int $taskId, string $status)
    {
        $task = HousekeepingTask::findOrFail($taskId);
        $task->update(['status' => $status]);

        // If completed, set room back to available
        if ($status === 'completed') {
            $task->room->update(['status' => 'available']);
        } elseif ($status === 'in_progress') {
            $task->room->update(['status' => 'cleaning']);
        }

        session()->flash('success', "Task status updated to {$status}.");
    }

    public function openRoomDetailModal(int $roomId): void
    {
        $this->detailRoomId = $roomId;
        $room = Room::findOrFail($roomId);
        $this->cleaningNotes = ''; // Cleaning information is empty by default
        $this->showRoomDetailModal = true;
        
        // Reset integrated issue form states
        $this->hasIssue = false;
        $this->issueType = 'missing_item';
        $this->issueDescription = '';
        
        // Initialize staff and dates for assignment if needed
        $this->selectedStaffId = User::whereHas('role', function ($q) {
            $q->where('slug', 'housekeeping');
        })->first()?->id;
        $this->scheduleDate = Carbon::now()->toDateString();
    }

    public function closeRoomDetailModal(): void
    {
        $this->showRoomDetailModal = false;
        $this->detailRoomId = null;
        $this->cleaningNotes = '';
        $this->hasIssue = false;
        $this->issueType = 'missing_item';
        $this->issueDescription = '';
    }

    public function assignTaskFromModal()
    {
        $this->selectedRoomId = $this->detailRoomId;
        $this->validate([
            'selectedRoomId' => 'required|exists:rooms,id',
            'selectedStaffId' => 'required|exists:users,id',
            'scheduleDate' => 'required|date',
        ]);

        $room = Room::find($this->selectedRoomId);
        if ($room->status === 'available') {
            $this->addError('selectedRoomId', 'This room is already clean and available (Vakant). Reassignment is blocked.');
            return;
        }

        HousekeepingTask::create([
            'room_id' => $this->selectedRoomId,
            'staff_id' => $this->selectedStaffId,
            'schedule_date' => $this->scheduleDate,
            'status' => 'in_progress',
        ]);

        $room->update(['status' => 'cleaning']);

        session()->flash('success', 'Housekeeping task assigned successfully. Cleaning is now in progress.');
        
        // Refresh details modal
        $this->openRoomDetailModal($this->detailRoomId);
    }

    public function completeCleaning(): void
    {
        $room = Room::findOrFail($this->detailRoomId);

        if ($this->hasIssue) {
            $this->validate([
                'issueDescription' => 'required|string|max:255',
                'issueType' => 'required|in:missing_item,maintenance',
            ]);
        }
        
        $task = HousekeepingTask::where('room_id', $room->id)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->latest()
            ->first();

        if ($task) {
            $task->update(['status' => 'completed']);
        }

        $isPreCheckIn = \App\Models\Booking::where('room_id', $room->id)->where('status', 'confirmed')->exists();
        $notes = $this->cleaningNotes ?: null;

        if ($isPreCheckIn) {
            $targetStatus = 'ready';
            if ($this->hasIssue) {
                $user = auth()->user();
                if ($this->issueType === 'maintenance') {
                    $targetStatus = 'maintenance';
                    $notes = $this->issueDescription;
                    $room->update([
                        'status' => 'maintenance',
                        'notes' => $notes,
                    ]);
                } else {
                    $room->update([
                        'status' => 'ready',
                        'notes' => $notes ?: $this->issueDescription,
                    ]);
                    app(\App\Services\NotificationService::class)->dispatchCustomAlert(
                        $room,
                        $user ?? \App\Models\User::first(),
                        "{$this->issueDescription} in Room {$room->room_number}",
                        'high',
                        true
                    );
                }
            } else {
                $room->update([
                    'status' => 'ready',
                    'notes' => $notes,
                ]);
            }
            session()->flash('success', "Room {$room->room_number} readiness inspection completed. Status set to Ready for Check-in.");
        } else {
            // Post-checkout: Keep status as 'cleaning'
            if ($this->hasIssue) {
                $user = auth()->user();
                if ($this->issueType === 'maintenance') {
                    $notes = $this->issueDescription;
                } else {
                    $notes = $notes ?: $this->issueDescription;
                    app(\App\Services\NotificationService::class)->dispatchCustomAlert(
                        $room,
                        $user ?? \App\Models\User::first(),
                        "{$this->issueDescription} in Room {$room->room_number}",
                        'high',
                        true
                    );
                }
            }
            $room->update([
                'status' => 'cleaning',
                'notes' => $notes,
            ]);
            session()->flash('success', "Cleaning & Inspection report submitted for Room {$room->room_number}. Room status remains cleaning pending release.");
        }

        $this->closeRoomDetailModal();
    }
}
