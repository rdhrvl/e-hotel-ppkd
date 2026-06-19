<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Room;
use App\Models\RoomType;
use App\Models\Branch;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Room Management')]
class Rooms extends Component
{
    // ── Add Room ──────────────────────────────────────────────────────
    public string $roomNumber = '';
    public ?int $roomTypeId = null;
    public ?float $priceOverride = null;
    public int $floor = 1;
    public string $notes = '';

    // ── Edit Room ─────────────────────────────────────────────────────
    public bool $showEditRoomModal = false;
    public ?int $editingRoomId = null;
    public string $editRoomNumber = '';
    public ?int $editRoomTypeId = null;
    public ?float $editPriceOverride = null;
    public int $editFloor = 1;
    public string $editStatus = 'available';
    public string $editNotes = '';

    // ── Add Room Type ─────────────────────────────────────────────────
    public string $typeName = '';
    public string $typeDescription = '';
    public float $typePrice = 150000;
    public int $typeCapacity = 2;
    public string $typeBedType = 'King';
    public bool $typeHasBreakfast = false;

    // ── Edit Room Type ────────────────────────────────────────────────
    public bool $showEditTypeModal = false;
    public ?int $editingTypeId = null;
    public string $editTypeName = '';
    public string $editTypeDescription = '';
    public float $editTypePrice = 150000;
    public int $editTypeCapacity = 2;
    public string $editTypeBedType = 'King';
    public bool $editTypeHasBreakfast = false;

    // ── Delete Confirm ────────────────────────────────────────────────
    public bool $showDeleteRoomModal = false;
    public ?int $deletingRoomId = null;
    public string $deletingRoomNumber = '';

    public bool $showDeleteTypeModal = false;
    public ?int $deletingTypeId = null;
    public string $deletingTypeName = '';

    protected $listeners = ['branchChanged' => '$refresh'];

    public function render(): \Illuminate\Contracts\View\View
    {
        // Enforce Admin checks
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $branchId = session('selected_branch_id', 1);

        $rooms = Room::with('roomType')->where('branch_id', $branchId)->orderBy('room_number')->get();
        $roomTypes = RoomType::withCount('rooms')->get();

        return view('livewire.dashboard.rooms', [
            'rooms'     => $rooms,
            'roomTypes' => $roomTypes,
        ]);
    }

    // ── ADD ROOM ──────────────────────────────────────────────────────
    public function addRoom(): void
    {
        $this->validate([
            'roomNumber'    => 'required|string|unique:rooms,room_number|max:10',
            'roomTypeId'    => 'required|exists:room_types,id',
            'priceOverride' => 'nullable|numeric|min:0',
            'floor'         => 'required|integer|min:1',
            'notes'         => 'nullable|string',
        ]);

        $branchId = session('selected_branch_id', 1);

        Room::create([
            'room_number'  => $this->roomNumber,
            'room_type_id' => $this->roomTypeId,
            'branch_id'    => $branchId,
            'price'        => $this->priceOverride ?: null,
            'floor'        => $this->floor,
            'status'       => 'available',
            'notes'        => $this->notes,
        ]);

        $this->roomNumber = '';
        $this->priceOverride = null;
        $this->floor = 1;
        $this->notes = '';
        session()->flash('success', 'Room added successfully.');
    }

    // ── EDIT ROOM ─────────────────────────────────────────────────────
    public function openEditRoomModal(int $roomId): void
    {
        $room = Room::findOrFail($roomId);
        $this->editingRoomId = $roomId;
        $this->editRoomNumber = $room->room_number;
        $this->editRoomTypeId = $room->room_type_id;
        $this->editPriceOverride = $room->price ? (float) $room->price : null;
        $this->editFloor = $room->floor;
        $this->editStatus = $room->status;
        $this->editNotes = $room->notes ?? '';
        $this->showEditRoomModal = true;
        $this->resetErrorBag();
    }

    public function closeEditRoomModal(): void
    {
        $this->showEditRoomModal = false;
        $this->editingRoomId = null;
        $this->resetErrorBag();
    }

    public function updateRoom(): void
    {
        $this->validate([
            'editRoomNumber'    => "required|string|max:10|unique:rooms,room_number,{$this->editingRoomId}",
            'editRoomTypeId'    => 'required|exists:room_types,id',
            'editPriceOverride' => 'nullable|numeric|min:0',
            'editFloor'         => 'required|integer|min:1',
            'editStatus'         => 'required|in:available,reserved,occupied,cleaning,maintenance',
            'editNotes'          => 'nullable|string',
        ]);

        $room = Room::findOrFail($this->editingRoomId);
        $room->update([
            'room_number'  => $this->editRoomNumber,
            'room_type_id' => $this->editRoomTypeId,
            'price'        => $this->editPriceOverride ?: null,
            'floor'        => $this->editFloor,
            'status'       => $this->editStatus,
            'notes'        => $this->editNotes,
        ]);

        session()->flash('success', "Room {$room->room_number} updated successfully.");
        $this->closeEditRoomModal();
    }

    // ── DELETE ROOM ───────────────────────────────────────────────────
    public function confirmDeleteRoom(int $roomId): void
    {
        $room = Room::findOrFail($roomId);
        if ($room->status !== 'available') {
            session()->flash('error', 'Cannot delete a room that is currently reserved or occupied.');
            return;
        }
        $this->deletingRoomId = $roomId;
        $this->deletingRoomNumber = $room->room_number;
        $this->showDeleteRoomModal = true;
    }

    public function closeDeleteRoomModal(): void
    {
        $this->showDeleteRoomModal = false;
        $this->deletingRoomId = null;
    }

    public function deleteRoom(int $roomId): void
    {
        $room = Room::findOrFail($roomId);
        if ($room->status !== 'available') {
            session()->flash('error', 'Cannot delete a room that is currently reserved or occupied.');
            $this->closeDeleteRoomModal();
            return;
        }

        $number = $room->room_number;
        $room->delete();
        session()->flash('success', "Room {$number} deleted successfully.");
        $this->closeDeleteRoomModal();
    }

    // ── ADD ROOM TYPE ─────────────────────────────────────────────────
    public function addRoomType(): void
    {
        $this->validate([
            'typeName'        => 'required|string|max:255',
            'typeDescription' => 'nullable|string',
            'typePrice'       => 'required|numeric|min:0',
            'typeCapacity'    => 'required|integer|min:1',
            'typeBedType'     => 'required|string|max:255',
            'typeHasBreakfast'=> 'required|boolean',
        ]);

        RoomType::create([
            'name'        => $this->typeName,
            'description' => $this->typeDescription,
            'base_price'  => $this->typePrice,
            'capacity'    => $this->typeCapacity,
            'bed_type'    => $this->typeBedType,
            'has_breakfast'=> $this->typeHasBreakfast,
        ]);

        $this->typeName = '';
        $this->typeDescription = '';
        $this->typePrice = 150000;
        $this->typeCapacity = 2;
        $this->typeBedType = 'King';
        $this->typeHasBreakfast = false;
        session()->flash('success', 'Room Type added successfully.');
    }

    // ── EDIT ROOM TYPE ────────────────────────────────────────────────
    public function openEditTypeModal(int $typeId): void
    {
        $type = RoomType::findOrFail($typeId);
        $this->editingTypeId = $typeId;
        $this->editTypeName = $type->name;
        $this->editTypeDescription = $type->description ?? '';
        $this->editTypePrice = (float) $type->base_price;
        $this->editTypeCapacity = $type->capacity;
        $this->editTypeBedType = $type->bed_type ?? 'King';
        $this->editTypeHasBreakfast = (bool) $type->has_breakfast;
        $this->showEditTypeModal = true;
        $this->resetErrorBag();
    }

    public function closeEditTypeModal(): void
    {
        $this->showEditTypeModal = false;
        $this->editingTypeId = null;
        $this->resetErrorBag();
    }

    public function updateRoomType(): void
    {
        $this->validate([
            'editTypeName'        => 'required|string|max:255',
            'editTypeDescription' => 'nullable|string',
            'editTypePrice'       => 'required|numeric|min:0',
            'editTypeCapacity'    => 'required|integer|min:1',
            'editTypeBedType'     => 'required|string|max:255',
            'editTypeHasBreakfast'=> 'required|boolean',
        ]);

        $type = RoomType::findOrFail($this->editingTypeId);
        $type->update([
            'name'        => $this->editTypeName,
            'description' => $this->editTypeDescription,
            'base_price'  => $this->editTypePrice,
            'capacity'    => $this->editTypeCapacity,
            'bed_type'    => $this->editTypeBedType,
            'has_breakfast'=> $this->editTypeHasBreakfast,
        ]);

        session()->flash('success', "Room Type \"{$type->name}\" updated successfully.");
        $this->closeEditTypeModal();
    }

    // ── DELETE ROOM TYPE ──────────────────────────────────────────────
    public function confirmDeleteRoomType(int $typeId): void
    {
        $type = RoomType::withCount('rooms')->findOrFail($typeId);
        if ($type->rooms_count > 0) {
            session()->flash('error', "Cannot delete \"{$type->name}\" — it still has {$type->rooms_count} room(s) assigned.");
            return;
        }
        $this->deletingTypeId = $typeId;
        $this->deletingTypeName = $type->name;
        $this->showDeleteTypeModal = true;
    }

    public function closeDeleteTypeModal(): void
    {
        $this->showDeleteTypeModal = false;
        $this->deletingTypeId = null;
    }

    public function deleteRoomType(int $typeId): void
    {
        $type = RoomType::withCount('rooms')->findOrFail($typeId);
        if ($type->rooms_count > 0) {
            session()->flash('error', 'Cannot delete this room type while rooms are assigned to it.');
            $this->closeDeleteTypeModal();
            return;
        }

        $name = $type->name;
        $type->delete();
        session()->flash('success', "Room Type \"{$name}\" deleted.");
        $this->closeDeleteTypeModal();
    }
}
