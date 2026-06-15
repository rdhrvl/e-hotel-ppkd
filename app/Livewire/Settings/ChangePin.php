<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Change PIN')]
class ChangePin extends Component
{
    public string $current_password = '';

    public string $pin = '';

    public string $pin_confirmation = '';

    /**
     * Validation rules for the PIN change form.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'current_password' => ['required'],
            'pin' => ['required', 'digits:6'],
            'pin_confirmation' => ['required', 'same:pin'],
        ];
    }

    /**
     * Validation messages.
     *
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'pin.digits' => 'PIN must be exactly 6 digits.',
            'pin_confirmation.same' => 'PIN confirmation does not match.',
        ];
    }

    /**
     * Update the user's PIN after verifying their password.
     */
    public function updatePin(): void
    {
        $this->validate();

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (! Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The password is incorrect.');

            return;
        }

        $user->update([
            'pin_hash' => Hash::make($this->pin),
        ]);

        NotificationService::pinChanged($user);

        $this->reset(['current_password', 'pin', 'pin_confirmation']);

        session()->flash('success', 'PIN updated successfully.');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.settings.change-pin');
    }
}
