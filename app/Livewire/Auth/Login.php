<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Login - IT Helpdesk System')]
#[Layout('components.layouts.app')]
class Login extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    #[Rule('required')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        if ($user && ! $user->is_active) {
            $this->addError('email', 'Akun Anda berstatus nonaktif. Silakan hubungi Administrator.');
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        $this->addError('email', 'Email atau kata sandi tidak cocok.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
