<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Create PIN')]
class CreatePin extends Component
{
    public string $pin = '';

    public string $pin_confirmation = '';

    /**
     * Validation rules for PIN creation.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
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
     * Create and store the user's PIN.
     */
    public function createPin(): void
    {
        $this->validate();

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->pin_hash = Hash::make($this->pin);
        $user->save();

        $this->reset(['pin', 'pin_confirmation']);

        session()->flash('success', 'PIN created successfully.');

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.auth.create-pin');
    }
}
