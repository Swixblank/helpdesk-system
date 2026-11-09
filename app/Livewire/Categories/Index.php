<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Manajemen Kategori - IT Helpdesk')]
#[Layout('components.layouts.app')]
class Index extends Component
{
    use WithPagination;

    public bool $isModalOpen = false;
    public ?int $editingCategoryId = null;

    public string $name = '';
    public string $description = '';
    public bool $is_active = true;

    public function mount(): void
    {
        abort_unless(auth()->user()->hasRole('administrator'), 403, 'Hanya Administrator yang dapat mengelola kategori.');
    }

    public function openCreateModal(): void
    {
        $this->reset(['name', 'description', 'editingCategoryId']);
        $this->is_active = true;
        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function openEditModal(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description ?? '';
        $this->is_active = $category->is_active;
        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->reset(['name', 'description', 'editingCategoryId']);
        $this->resetValidation();
    }

    public function save(): void
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categories', 'name')->ignore($this->editingCategoryId),
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
        ];

        $this->validate($rules);

        Category::updateOrCreate(
            ['id' => $this->editingCategoryId],
            [
                'name' => trim($this->name),
                'description' => trim($this->description) ?: null,
                'is_active' => $this->is_active,
            ]
        );

        session()->flash('status', $this->editingCategoryId ? 'Kategori berhasil diperbarui.' : 'Kategori baru berhasil ditambahkan.');
        $this->closeModal();
    }

    public function toggleStatus(int $id): void
    {
        $category = Category::findOrFail($id);
        $category->update(['is_active' => ! $category->is_active]);

        $statusText = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('status', "Kategori {$category->name} berhasil {$statusText}.");
    }

    public function render()
    {
        return view('livewire.categories.index', [
            'categories' => Category::withCount('tickets')->orderBy('name')->paginate(10),
        ]);
    }
}
