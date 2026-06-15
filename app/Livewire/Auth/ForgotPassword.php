<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Forgot Password')]
class ForgotPassword extends Component
{
    public string $email = '';

    /**
     * Validation rules for the forgot password form.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendResetLink(): void
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->reset('email');

            session()->flash('success', __($status));

            return;
        }

        $this->addError('email', __($status));
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.auth.forgot-password');
    }
}
