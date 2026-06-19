<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\AuditLog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Audit Trail Logs')]
class AuditLogs extends Component
{
    public string $search = '';

    public function render(): \Illuminate\Contracts\View\View
    {
        $logs = AuditLog::with('user')
            ->when($this->search, function ($query) {
                $query->where('action', 'like', '%' . $this->search . '%')
                      ->orWhere('entity_type', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.dashboard.audit-logs', [
            'logs' => $logs,
        ]);
    }
}
