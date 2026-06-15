<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Change Password')]
class ChangePassword extends Component
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Validation rules for the password change form.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Update the user's password after verifying the current one.
     */
    public function updatePassword(): void
    {
        $this->validate();

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (! Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');

            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        NotificationService::passwordChanged($user);

        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('success', 'Password updated successfully.');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.settings.change-password');
    }
}
