<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Title('Manajemen Pengguna & Staf - IT Helpdesk')]
#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public bool $isModalOpen = false;
    public ?int $editingUserId = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $role = 'employee';
    public bool $is_active = true;

    // Filter
    public string $search = '';
    public string $roleFilter = '';
    public string $statusFilter = '';

    public function mount(): void
    {
        abort_unless(auth()->user()->hasRole('administrator'), 403, 'Akses terbatas untuk Administrator.');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'roleFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->reset(['name', 'email', 'password', 'editingUserId']);
        $this->role = 'employee';
        $this->is_active = true;
        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function openEditModal(int $id): void
    {
        $user = User::with('roles')->findOrFail($id);
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->role = $user->getRoleNames()->first() ?? 'employee';
        $this->is_active = $user->is_active;
        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->reset(['name', 'email', 'password', 'editingUserId']);
        $this->resetValidation();
    }

    public function save(): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->editingUserId),
            ],
            'role' => ['required', 'in:administrator,it_support,employee'],
            'is_active' => ['boolean'],
        ];

        if (! $this->editingUserId) {
            $rules['password'] = ['required', 'string', 'min:6'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:6'];
        }

        $validated = $this->validate($rules);

        if ($this->editingUserId) {
            $user = User::findOrFail($this->editingUserId);
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'is_active' => $validated['is_active'],
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);
            $user->syncRoles([$validated['role']]);

            session()->flash('status', "Data pengguna {$user->name} berhasil diperbarui.");
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_active' => $validated['is_active'],
            ]);
            $user->syncRoles([$validated['role']]);

            session()->flash('status', "Pengguna baru {$user->name} berhasil ditambahkan.");
        }

        $this->closeModal();
    }

    public function toggleStatus(int $id): void
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
            return;
        }

        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('status', "Akun {$user->name} berhasil {$statusText}.");
    }

    public function render()
    {
        $query = User::with(['roles', 'tickets', 'assignments']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $query->role($this->roleFilter);
        }

        if ($this->statusFilter !== '') {
            $query->where('is_active', (bool) $this->statusFilter);
        }

        $users = $query->orderBy('name')->paginate(10);
        $roles = Role::all();

        return view('livewire.users.index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
