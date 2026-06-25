<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Login')]
class Login extends Component
{
    public string $email = '';

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
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ];
    }

    /** Demo role → seeded email. ponytail: dev convenience, drop before prod. */
    public const DEMO_ACCOUNTS = [
        'superadmin' => 'superadmin@example.com',
        'admin' => 'admin@example.com',
        'front_desk' => 'frontdesk@example.com',
        'housekeeping' => 'housekeeping@example.com',
        'fnb' => 'fnb@example.com',
    ];

    /** One-click login as a seeded demo role. */
    public function loginAs(string $role): void
    {
        if (! isset(self::DEMO_ACCOUNTS[$role])) {
            return;
        }

        Auth::login(User::where('email', self::DEMO_ACCOUNTS[$role])->firstOrFail(), true);
        session()->regenerate();
        $this->redirect(route(Auth::user()->homeRoute()));
    }

    /**
     * Attempt to authenticate the user.
     */
    public function login(): void
    {
        $this->validate();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Invalid credentials.');

            return;
        }

        session()->regenerate();

        $this->redirect(route(Auth::user()->homeRoute()));
    }

    public function render(): View
    {
        return view('livewire.auth.login');
    }
}
