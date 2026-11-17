<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Tambah Pengguna Baru - IT Helpdesk')]
#[Layout('components.layouts.app')]
class Create extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'employee';
    public bool $is_active = true;

    public function mount(): void
    {
        abort_unless(auth()->user()->hasRole('administrator'), 403, 'Akses terbatas untuk Administrator.');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:administrator,it_support,employee'],
            'is_active' => ['boolean'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap pengguna wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Email kantor wajib diisi.',
            'email.email' => 'Format email kantor tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar dalam sistem.',
            'password.required' => 'Kata sandi akun wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'role.required' => 'Peran / hak akses wajib dipilih.',
            'role.in' => 'Pilihan peran pengguna tidak valid.',
        ];
    }

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => strtolower($this->email),
            'password' => Hash::make($this->password),
            'is_active' => $this->is_active,
        ]);

        $user->syncRoles([$this->role]);

        session()->flash('status', "Pengguna {$user->name} ({$this->role}) berhasil didaftarkan ke sistem.");

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.users.create');
    }
}
