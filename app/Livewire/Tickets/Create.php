<?php

namespace App\Livewire\Tickets;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketStatusLog;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Buat Tiket Kendala Baru - IT Helpdesk')]
#[Layout('components.layouts.app')]
class Create extends Component
{
    use WithFileUploads;

    public string $subject = '';
    public string $category_id = '';
    public string $priority = 'medium';
    public string $description = '';
    public $attachment;

    public function mount(): void
    {
        // Default category ke kategori aktif pertama jika ada
        $firstCategory = Category::where('is_active', true)->orderBy('name')->first();
        if ($firstCategory) {
            $this->category_id = (string) $firstCategory->id;
        }
    }

    protected function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id,is_active,1'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'description' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // maks 5MB
        ];
    }

    protected function messages(): array
    {
        return [
            'subject.required' => 'Subjek tiket wajib diisi.',
            'subject.max' => 'Subjek tiket maksimal 150 karakter.',
            'category_id.required' => 'Kategori kendala wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid atau sudah nonaktif.',
            'priority.required' => 'Tingkat prioritas wajib dipilih.',
            'description.required' => 'Deskripsi kendala wajib diisi.',
            'attachment.mimes' => 'Format file lampiran hanya diperbolehkan JPG, PNG, atau PDF.',
            'attachment.max' => 'Ukuran file lampiran maksimal 5 MB.',
        ];
    }

    public function save()
    {
        $this->validate();

        return DB::transaction(function () {
            // Generasi nomor tiket unik: TICK-YYYYMM-XXXX
            $prefix = 'TICK-' . now()->format('Ym') . '-';
            $latestTicket = Ticket::where('ticket_number', 'like', $prefix . '%')
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            $nextSequence = 1;
            if ($latestTicket) {
                $lastNumber = (int) substr($latestTicket->ticket_number, -4);
                $nextSequence = $lastNumber + 1;
            }

            $ticketNumber = $prefix . str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);

            // Simpan tiket baru
            $ticket = Ticket::create([
                'ticket_number' => $ticketNumber,
                'created_by' => auth()->id(),
                'category_id' => $this->category_id,
                'subject' => $this->subject,
                'description' => $this->description,
                'priority' => $this->priority,
                'status' => 'open',
            ]);

            // Simpan lampiran jika diunggah
            if ($this->attachment) {
                $path = $this->attachment->store('tickets/attachments', 'local');

                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'comment_id' => null,
                    'uploaded_by' => auth()->id(),
                    'original_name' => $this->attachment->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $this->attachment->getMimeType(),
                    'file_size' => $this->attachment->getSize(),
                ]);
            }

            // Catat log status awal
            TicketStatusLog::create([
                'ticket_id' => $ticket->id,
                'changed_by' => auth()->id(),
                'old_status' => null,
                'new_status' => 'open',
                'notes' => 'Tiket kendala berhasil dibuat dan menunggu penanganan.',
                'created_at' => now(),
            ]);

            session()->flash('status', "Tiket {$ticket->ticket_number} berhasil dikirim dan siap ditangani.");
            return redirect()->route('tickets.show', $ticket->id);
        });
    }

    public function render()
    {
        return view('livewire.tickets.create', [
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
