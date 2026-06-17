<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Login')]
class Login extends Component
{
    public string $phone = '';

    public string $password = '';

    public bool $remember = false;

    /**
     * Validation rules for the login form.
     *
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'phone' => ['required'],
            'password' => ['required', 'min:6'],
        ];
    }

    /**
     * Attempt to authenticate the user.
     */
    public function login(): void
    {
        $this->validate();

        if (! Auth::attempt(['phone' => $this->phone, 'password' => $this->password], $this->remember)) {
            $this->addError('phone', 'Invalid credentials.');

            return;
        }

        session()->regenerate();

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.auth.login');
    }
}
