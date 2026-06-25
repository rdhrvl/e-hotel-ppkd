<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\FoodOrder;
use App\Models\FoodOrderItem;
use App\Models\Service;
use App\Services\NotificationService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Food & Beverage Operations')]
class Fnb extends Component
{
    use WithPagination;

    // View state
    public string $activeTab = 'orders'; // 'orders' or 'menu'

    public string $ordersFilter = 'active'; // 'active', 'completed', 'all'

    // Menu Item properties (Add)
    public bool $showAddModal = false;

    public string $name = '';

    public float $price = 0;

    public string $category = 'Main Course';

    public string $description = '';

    public bool $isActive = true;

    public string $imagePath = '';

    // Menu Item properties (Edit)
    public bool $showEditModal = false;

    public ?int $editingMenuItemId = null;

    public string $editName = '';

    public float $editPrice = 0;

    public string $editCategory = 'Main Course';

    public string $editDescription = '';

    public bool $editIsActive = true;

    public string $editImagePath = '';

    // Menu Item properties (Delete)
    public bool $showDeleteModal = false;

    public ?int $deletingMenuItemId = null;

    public string $deletingMenuItemName = '';

    private const DEFAULT_CATEGORIES = [
        'Appetizer',
        'Main Course',
        'Beverage',
        'Dessert',
        'Snack',
    ];

    public array $categories = self::DEFAULT_CATEGORIES;

    public string $newCategory = '';

    public string $categoryFilter = '';

    // Category Edit
    public bool $showEditCategoryModal = false;

    public string $editingCategory = '';

    public string $editCategoryName = '';

    // Category Delete
    public bool $showDeleteCategoryModal = false;

    public string $deletingCategory = '';

    public int $deletingCategoryItemCount = 0;

    public function mount(): void
    {
        if (auth()->user()->cannot('accessFnb')) {
            abort(403, 'Unauthorized access to F&B Dashboard.');
        }

        $defaults = $this->categories;
        $dbCategories = Service::where('type', 'f_and_b')
            ->pluck('category')->unique()->filter()->toArray();
        $sessionCategories = session('fnb_custom_categories', []);

        $merged = array_values(array_unique(array_merge($defaults, $dbCategories, $sessionCategories)));
        sort($merged);
        $this->categories = $merged;
    }

    public function addCategory(): void
    {
        $this->validate(['newCategory' => 'required|string|max:100']);
        $trimmed = trim($this->newCategory);

        if (! in_array($trimmed, $this->categories)) {
            $this->categories[] = $trimmed;
            sort($this->categories);
            $defaults = self::DEFAULT_CATEGORIES;
            session(['fnb_custom_categories' => array_values(array_diff($this->categories, $defaults))]);
        }

        $this->newCategory = '';
        $this->resetErrorBag('newCategory');
    }

    public function removeCategory(string $category): void
    {
        $inUse = Service::where('type', 'f_and_b')->where('category', $category)->exists();
        if ($inUse) {
            session()->flash('error', "Cannot remove \"{$category}\" — it is used by existing menu items.");

            return;
        }

        $this->categories = array_values(array_filter($this->categories, fn ($c) => $c !== $category));
        $defaults = ['Appetizer', 'Main Course', 'Beverage', 'Dessert', 'Snack'];
        session(['fnb_custom_categories' => array_values(array_diff($this->categories, $defaults))]);
        session()->flash('success', "Category \"{$category}\" removed.");
    }

    public function openEditCategoryModal(string $category): void
    {
        $this->editingCategory = $category;
        $this->editCategoryName = $category;
        $this->showEditCategoryModal = true;
        $this->resetErrorBag('editCategoryName');
    }

    public function closeEditCategoryModal(): void
    {
        $this->showEditCategoryModal = false;
        $this->editingCategory = '';
        $this->editCategoryName = '';
        $this->resetErrorBag('editCategoryName');
    }

    public function updateCategory(): void
    {
        $this->validate(['editCategoryName' => 'required|string|max:100']);
        $trimmed = trim($this->editCategoryName);

        if ($trimmed === $this->editingCategory) {
            $this->closeEditCategoryModal();

            return;
        }

        if (in_array($trimmed, $this->categories)) {
            $this->addError('editCategoryName', "Category \"{$trimmed}\" already exists.");

            return;
        }

        // Rename category on all existing menu items
        Service::where('type', 'f_and_b')
            ->where('category', $this->editingCategory)
            ->update(['category' => $trimmed]);

        // Replace in local array
        $this->categories = array_values(array_map(
            fn ($c) => $c === $this->editingCategory ? $trimmed : $c,
            $this->categories
        ));
        sort($this->categories);

        $defaults = ['Appetizer', 'Main Course', 'Beverage', 'Dessert', 'Snack'];
        session(['fnb_custom_categories' => array_values(array_diff($this->categories, $defaults))]);

        if ($this->categoryFilter === $this->editingCategory) {
            $this->categoryFilter = $trimmed;
        }

        session()->flash('success', "Category renamed to \"{$trimmed}\".");
        $this->closeEditCategoryModal();
    }

    public function openDeleteCategoryModal(string $category): void
    {
        $this->deletingCategory = $category;
        $this->deletingCategoryItemCount = Service::where('type', 'f_and_b')
            ->where('category', $category)->count();
        $this->showDeleteCategoryModal = true;
    }

    public function closeDeleteCategoryModal(): void
    {
        $this->showDeleteCategoryModal = false;
        $this->deletingCategory = '';
        $this->deletingCategoryItemCount = 0;
    }

    public function deleteCategory(): void
    {
        if ($this->deletingCategoryItemCount > 0) {
            session()->flash('error', "Cannot delete \"{$this->deletingCategory}\" — reassign or remove its {$this->deletingCategoryItemCount} menu items first.");
            $this->closeDeleteCategoryModal();

            return;
        }

        $this->categories = array_values(array_filter($this->categories, fn ($c) => $c !== $this->deletingCategory));
        $defaults = ['Appetizer', 'Main Course', 'Beverage', 'Dessert', 'Snack'];
        session(['fnb_custom_categories' => array_values(array_diff($this->categories, $defaults))]);

        if ($this->categoryFilter === $this->deletingCategory) {
            $this->categoryFilter = '';
        }

        session()->flash('success', "Category \"{$this->deletingCategory}\" deleted.");
        $this->closeDeleteCategoryModal();
    }

    public function render(): View
    {
        // Enforce access in render just in case
        if (auth()->user()->cannot('accessFnb')) {
            abort(403, 'Unauthorized access to F&B Dashboard.');
        }

        // Fetch Menu Items (paginated if we want, or just get all)
        $menuItems = Service::where('type', 'f_and_b')
            ->when($this->categoryFilter, fn ($q) => $q->where('category', $this->categoryFilter))
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        // Fetch Food Orders
        $ordersQuery = FoodOrder::with(['booking.room.roomType', 'booking.guest', 'items.service'])
            ->latest();

        if ($this->ordersFilter === 'active') {
            $ordersQuery->whereIn('status', ['processed', 'preparing', 'delivered']);
        } elseif ($this->ordersFilter === 'completed') {
            $ordersQuery->where('status', 'completed');
        }

        $orders = $ordersQuery->get();

        // Calculate quick stats
        $stats = [
            'total_active' => FoodOrder::whereIn('status', ['processed', 'preparing', 'delivered'])->count(),
            'processed' => FoodOrder::where('status', 'processed')->count(),
            'preparing' => FoodOrder::where('status', 'preparing')->count(),
            'delivered' => FoodOrder::where('status', 'delivered')->count(),
            'completed' => FoodOrder::where('status', 'completed')->count(),
        ];

        $categoryCounts = Service::where('type', 'f_and_b')
            ->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        return view('livewire.dashboard.fnb', [
            'menuItems' => $menuItems,
            'orders' => $orders,
            'stats' => $stats,
            'categoryCounts' => $categoryCounts,
        ]);
    }

    // ── Menu Management CRUD ─────────────────────────────────────────────

    public function openAddModal(): void
    {
        $this->reset(['name', 'price', 'category', 'description', 'isActive', 'imagePath']);
        $this->category = 'Main Course';
        $this->isActive = true;
        $this->showAddModal = true;
        $this->resetErrorBag();
    }

    public function closeAddModal(): void
    {
        $this->showAddModal = false;
        $this->resetErrorBag();
    }

    public function createMenuItem(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'imagePath' => 'nullable|string|max:2048',
        ]);

        Service::create([
            'name' => $this->name,
            'price' => $this->price,
            'type' => 'f_and_b',
            'category' => $this->category,
            'description' => $this->description,
            'is_active' => $this->isActive,
            'image_path' => $this->imagePath ?: null,
        ]);

        session()->flash('success', "Menu item \"{$this->name}\" added successfully.");
        $this->closeAddModal();
    }

    public function openEditModal(int $id): void
    {
        $item = Service::findOrFail($id);
        $this->editingMenuItemId = $id;
        $this->editName = $item->name;
        $this->editPrice = (float) $item->price;
        $this->editCategory = $item->category;
        $this->editDescription = $item->description ?? '';
        $this->editIsActive = (bool) $item->is_active;
        $this->editImagePath = $item->image_path ?? '';
        $this->showEditModal = true;
        $this->resetErrorBag();
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingMenuItemId = null;
        $this->resetErrorBag();
    }

    public function updateMenuItem(): void
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editPrice' => 'required|numeric|min:0',
            'editCategory' => 'required|string|max:255',
            'editDescription' => 'nullable|string',
            'editImagePath' => 'nullable|string|max:2048',
        ]);

        $item = Service::findOrFail($this->editingMenuItemId);
        $item->update([
            'name' => $this->editName,
            'price' => $this->editPrice,
            'category' => $this->editCategory,
            'description' => $this->editDescription,
            'is_active' => $this->editIsActive,
            'image_path' => $this->editImagePath ?: null,
        ]);

        session()->flash('success', "Menu item \"{$item->name}\" updated successfully.");
        $this->closeEditModal();
    }

    public function confirmDelete(int $id): void
    {
        $item = Service::findOrFail($id);
        $this->deletingMenuItemId = $id;
        $this->deletingMenuItemName = $item->name;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingMenuItemId = null;
    }

    public function deleteMenuItem(): void
    {
        $id = $this->deletingMenuItemId;

        // Safety check: is this menu item referenced by orders still in progress?
        $inProgress = FoodOrderItem::where('service_id', $id)
            ->whereHas('foodOrder', function ($query) {
                $query->whereIn('status', ['processed', 'preparing', 'delivered']);
            })
            ->exists();

        if ($inProgress) {
            session()->flash('error', "Cannot delete \"{$this->deletingMenuItemName}\" because it is associated with active orders currently in progress.");
            $this->closeDeleteModal();

            return;
        }

        $item = Service::findOrFail($id);
        $name = $item->name;
        $item->delete();

        session()->flash('success', "Menu item \"{$name}\" deleted successfully.");
        $this->closeDeleteModal();
    }

    // ── Order Tracking Status Flow ────────────────────────────────────────

    public function advanceOrderStatus(int $orderId, NotificationService $notificationService): void
    {
        $order = FoodOrder::with('booking.room.roomType', 'booking.guest')->findOrFail($orderId);

        $statusSequence = [
            'processed' => 'preparing',
            'preparing' => 'delivered',
            'delivered' => 'completed',
        ];

        $currentStatus = $order->status;

        if (! isset($statusSequence[$currentStatus])) {
            return; // Already completed or invalid
        }

        $nextStatus = $statusSequence[$currentStatus];
        $order->update(['status' => $nextStatus]);

        // If completed, trigger alert to front_desk role
        if ($nextStatus === 'completed') {
            $booking = $order->booking;
            $message = "Food order #{$order->id} for Room {$booking->room->room_number} ({$booking->guest->name}) is completed and delivered!";

            $notificationService->dispatchFoodOrderAlert($booking, $message, 'front_desk', 'medium', false);
            session()->flash('success', "Order #{$order->id} marked as completed and Front Desk has been notified.");
        } else {
            $labels = [
                'preparing' => 'Order Being Prepared',
                'delivered' => 'Order Delivered',
            ];
            $label = $labels[$nextStatus] ?? $nextStatus;
            session()->flash('success', "Order #{$order->id} status updated to: {$label}");
        }
    }
}
