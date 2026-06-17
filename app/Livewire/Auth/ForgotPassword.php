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

    public bool $sent = false;

    /**
     * Send the password reset link to the given email address.
     */
    public function sendResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->sent = true;
            session()->flash('success', __($status));
        } else {
            $this->addError('email', __($status));
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.auth.forgot-password');
    }
}
