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

#[Layout('layouts.app')]
#[Title('Housekeeping Dashboard')]
class Housekeeping extends Component
{
    public string $filterStatus = '';
    
    // Add/Assign task form states
    public bool $showAssignModal = false;
    public ?int $selectedRoomId = null;
    public ?int $selectedStaffId = null;
    public string $scheduleDate = '';

    protected $listeners = ['branchChanged' => '$refresh'];

    public function mount()
    {
        $this->scheduleDate = Carbon::now()->toDateString();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $branchId = session('selected_branch_id', 1);

        // Fetch housekeeping tasks scoped to current branch
        $tasks = HousekeepingTask::with(['room.roomType', 'staff'])
            ->whereHas('room', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy('schedule_date', 'asc')
            ->get();

        // Get dirty/cleaning rooms that need tasks
        $unassignedRooms = Room::where('branch_id', $branchId)
            ->whereIn('status', ['cleaning', 'maintenance'])
            ->whereDoesntHave('housekeepingTasks', function ($q) {
                $q->where('status', '!=', 'completed');
            })
            ->get();

        // Housekeeping staff
        $housekeepingStaff = User::whereHas('role', function ($q) {
            $q->where('slug', 'housekeeping');
        })->get();

        return view('livewire.dashboard.housekeeping', [
            'tasks' => $tasks,
            'unassignedRooms' => $unassignedRooms,
            'housekeepingStaff' => $housekeepingStaff,
        ]);
    }

    public function openAssignModal(int $roomId)
    {
        $this->selectedRoomId = $roomId;
        $this->selectedStaffId = User::whereHas('role', function ($q) {
            $q->where('slug', 'housekeeping');
        })->first()?->id;
        $this->showAssignModal = true;
    }

    public function closeAssignModal()
    {
        $this->showAssignModal = false;
        $this->resetErrorBag();
    }

    public function assignTask()
    {
        $this->validate([
            'selectedRoomId' => 'required|exists:rooms,id',
            'selectedStaffId' => 'required|exists:users,id',
            'scheduleDate' => 'required|date',
        ]);

        HousekeepingTask::create([
            'room_id' => $this->selectedRoomId,
            'staff_id' => $this->selectedStaffId,
            'schedule_date' => $this->scheduleDate,
            'status' => 'scheduled',
        ]);

        // Automatically set room to cleaning if not already
        $room = Room::find($this->selectedRoomId);
        if ($room->status === 'available') {
            $room->update(['status' => 'cleaning']);
        }

        session()->flash('success', 'Housekeeping task assigned successfully.');
        $this->closeAssignModal();
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
}
