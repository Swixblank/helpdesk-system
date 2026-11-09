<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Models\TicketAttachment;
use App\Models\TicketComment;
use App\Models\TicketStatusLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Detail Tiket Kendala - IT Helpdesk')]
#[Layout('components.layouts.app')]
class Show extends Component
{
    use WithFileUploads;

    public int $ticketId;

    // Form inputs untuk aksi status & penugasan
    public ?int $assignToUserId = null;
    public string $assignmentNotes = '';
    public string $resolutionNotes = '';
    public string $reopenNotes = '';

    // Form input komentar (Hari 11 & 12)
    public string $commentBody = '';
    public $commentAttachment = null;

    // Modal state
    public bool $showAssignModal = false;
    public bool $showResolveModal = false;
    public bool $showReopenModal = false;

    public function mount(int $ticket): void
    {
        $this->ticketId = $ticket;
        $this->authorizeAccess();
    }

    protected function getTicket(): Ticket
    {
        return Ticket::with([
            'creator',
            'category',
            'activeAssignment.assignedTo',
            'assignments.assignedTo',
            'assignments.assignedBy',
            'ticketAttachments.uploader',
            'comments.user',
            'comments.attachments.uploader',
            'statusLogs.actor',
        ])->findOrFail($this->ticketId);
    }

    protected function authorizeAccess(): void
    {
        $ticket = Ticket::findOrFail($this->ticketId);
        $user = Auth::user();

        if ($user->hasRole('employee')) {
            abort_unless($ticket->created_by === $user->id, 403, 'Anda tidak memiliki hak akses untuk melihat tiket ini.');
        } elseif ($user->hasRole('it_support')) {
            $isAssigned = $ticket->activeAssignment && $ticket->activeAssignment->assigned_to === $user->id;
            $isOpen = $ticket->status === 'open';
            abort_unless($isAssigned || $isOpen, 403, 'Anda tidak memiliki hak akses untuk tiket ini.');
        }
    }

    // --- Hari 9: Penugasan IT Support (FR-06) ---

    public function claimTicket(): void
    {
        $ticket = $this->getTicket();
        $user = Auth::user();

        abort_unless($user->hasRole('it_support') && $ticket->status === 'open' && ! $ticket->activeAssignment, 403);

        DB::transaction(function () use ($ticket, $user) {
            TicketAssignment::create([
                'ticket_id' => $ticket->id,
                'assigned_to' => $user->id,
                'assigned_by' => $user->id,
                'assigned_at' => now(),
                'notes' => 'Petugas IT Support mengambil tiket secara mandiri.',
            ]);

            $ticket->update(['status' => 'assigned']);

            TicketStatusLog::create([
                'ticket_id' => $ticket->id,
                'changed_by' => $user->id,
                'old_status' => 'open',
                'new_status' => 'assigned',
                'notes' => "Tiket diambil dan ditangani langsung oleh {$user->name}.",
                'created_at' => now(),
            ]);
        });

        session()->flash('status', 'Tiket berhasil Anda ambil dan siap ditangani.');
    }

    public function openAssignModal(): void
    {
        $ticket = $this->getTicket();
        $this->assignToUserId = $ticket->activeAssignment ? $ticket->activeAssignment->assigned_to : null;
        $this->assignmentNotes = '';
        $this->showAssignModal = true;
    }

    public function assignTicket(): void
    {
        abort_unless(Auth::user()->hasRole('administrator'), 403);

        $this->validate([
            'assignToUserId' => ['required', 'exists:users,id'],
            'assignmentNotes' => ['nullable', 'string', 'max:500'],
        ]);

        $ticket = $this->getTicket();
        $assignedUser = User::findOrFail($this->assignToUserId);

        DB::transaction(function () use ($ticket, $assignedUser) {
            // Tutup penugasan aktif sebelumnya jika ada
            if ($ticket->activeAssignment) {
                $ticket->activeAssignment->update(['unassigned_at' => now()]);
            }

            // Catat penugasan baru
            TicketAssignment::create([
                'ticket_id' => $ticket->id,
                'assigned_to' => $assignedUser->id,
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
                'notes' => $this->assignmentNotes ?: null,
            ]);

            $oldStatus = $ticket->status;
            if ($ticket->status === 'open') {
                $ticket->update(['status' => 'assigned']);
            }

            TicketStatusLog::create([
                'ticket_id' => $ticket->id,
                'changed_by' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $ticket->status,
                'notes' => "Administrator menugaskan tiket kepada {$assignedUser->name}." . ($this->assignmentNotes ? " Catatan: {$this->assignmentNotes}" : ''),
                'created_at' => now(),
            ]);
        });

        $this->showAssignModal = false;
        session()->flash('status', "Tiket berhasil ditugaskan kepada {$assignedUser->name}.");
    }

    // --- Hari 10: Pengelolaan Alur Status (FR-07) ---

    public function startProgress(): void
    {
        $ticket = $this->getTicket();
        $user = Auth::user();

        $canStart = $user->hasRole('administrator') || 
            ($user->hasRole('it_support') && $ticket->activeAssignment && $ticket->activeAssignment->assigned_to === $user->id);

        abort_unless($canStart && in_array($ticket->status, ['assigned', 'reopened']), 403);

        DB::transaction(function () use ($ticket, $user) {
            $oldStatus = $ticket->status;
            $ticket->update(['status' => 'in_progress']);

            TicketStatusLog::create([
                'ticket_id' => $ticket->id,
                'changed_by' => $user->id,
                'old_status' => $oldStatus,
                'new_status' => 'in_progress',
                'notes' => 'Petugas memulai pemeriksaan dan pengerjaan kendala.',
                'created_at' => now(),
            ]);
        });

        session()->flash('status', 'Status tiket berhasil diubah menjadi In Progress.');
    }

    public function openResolveModal(): void
    {
        $this->resolutionNotes = '';
        $this->resetValidation();
        $this->showResolveModal = true;
    }

    public function resolveTicket(): void
    {
        $ticket = $this->getTicket();
        $user = Auth::user();

        $canResolve = $user->hasRole('administrator') || 
            ($user->hasRole('it_support') && $ticket->activeAssignment && $ticket->activeAssignment->assigned_to === $user->id);

        abort_unless($canResolve && $ticket->status === 'in_progress', 403);

        $this->validate([
            'resolutionNotes' => ['required', 'string', 'min:5'],
        ], [
            'resolutionNotes.required' => 'Catatan solusi penyelesaian wajib diisi.',
            'resolutionNotes.min' => 'Catatan penyelesaian minimal 5 karakter.',
        ]);

        DB::transaction(function () use ($ticket, $user) {
            $ticket->update([
                'status' => 'resolved',
                'resolved_at' => now(),
            ]);

            TicketStatusLog::create([
                'ticket_id' => $ticket->id,
                'changed_by' => $user->id,
                'old_status' => 'in_progress',
                'new_status' => 'resolved',
                'notes' => 'Solusi perbaikan: ' . $this->resolutionNotes,
                'created_at' => now(),
            ]);
        });

        $this->showResolveModal = false;
        session()->flash('status', 'Tiket berhasil diselesaikan dan menunggu konfirmasi pelapor.');
    }

    public function closeTicket(): void
    {
        $ticket = $this->getTicket();
        $user = Auth::user();

        $canClose = $user->hasRole('administrator') || ($user->id === $ticket->created_by);
        abort_unless($canClose && $ticket->status === 'resolved', 403);

        DB::transaction(function () use ($ticket, $user) {
            $ticket->update([
                'status' => 'closed',
                'closed_at' => now(),
            ]);

            TicketStatusLog::create([
                'ticket_id' => $ticket->id,
                'changed_by' => $user->id,
                'old_status' => 'resolved',
                'new_status' => 'closed',
                'notes' => 'Pelapor telah mengonfirmasi bahwa kendala sudah tuntas diselesaikan.',
                'created_at' => now(),
            ]);
        });

        session()->flash('status', 'Tiket telah berhasil ditutup secara permanen.');
    }

    public function openReopenModal(): void
    {
        $this->reopenNotes = '';
        $this->resetValidation();
        $this->showReopenModal = true;
    }

    public function reopenTicket(): void
    {
        $ticket = $this->getTicket();
        $user = Auth::user();

        $canReopen = $user->hasRole('administrator') || ($user->id === $ticket->created_by);
        abort_unless($canReopen && $ticket->status === 'resolved', 403);

        $this->validate([
            'reopenNotes' => ['required', 'string', 'min:5'],
        ], [
            'reopenNotes.required' => 'Alasan pembukaan ulang tiket wajib diisi.',
            'reopenNotes.min' => 'Alasan pembukaan ulang minimal 5 karakter.',
        ]);

        DB::transaction(function () use ($ticket, $user) {
            $ticket->update([
                'status' => 'reopened',
            ]);

            TicketStatusLog::create([
                'ticket_id' => $ticket->id,
                'changed_by' => $user->id,
                'old_status' => 'resolved',
                'new_status' => 'reopened',
                'notes' => 'Pembukaan ulang diajukan: ' . $this->reopenNotes,
                'created_at' => now(),
            ]);
        });

        $this->showReopenModal = false;
        session()->flash('status', 'Tiket telah dibuka kembali untuk penanganan ulang oleh teknisi.');
    }

    // --- Hari 11 & 12: Komentar Tiket & Lampiran Komentar (FR-08 & FR-09) ---

    public function postComment(): void
    {
        $ticket = $this->getTicket();
        abort_if($ticket->status === 'closed', 403, 'Komentar tidak dapat ditambahkan pada tiket yang telah ditutup.');

        $this->validate([
            'commentBody' => ['required', 'string', 'min:2', 'max:2000'],
            'commentAttachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'commentBody.required' => 'Isi komentar tidak boleh kosong.',
            'commentBody.min' => 'Komentar minimal 2 karakter.',
            'commentAttachment.mimes' => 'Format file hanya diperbolehkan JPG, PNG, atau PDF.',
            'commentAttachment.max' => 'Ukuran file lampiran maksimal 5 MB.',
        ]);

        DB::transaction(function () use ($ticket) {
            $comment = TicketComment::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'body' => trim($this->commentBody),
            ]);

            if ($this->commentAttachment) {
                $path = $this->commentAttachment->store('tickets/attachments', 'local');

                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'comment_id' => $comment->id,
                    'uploaded_by' => Auth::id(),
                    'original_name' => $this->commentAttachment->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $this->commentAttachment->getMimeType(),
                    'file_size' => $this->commentAttachment->getSize(),
                ]);
            }
        });

        $this->reset(['commentBody', 'commentAttachment']);
        session()->flash('status', 'Komentar berhasil dikirim.');
    }

    public function downloadAttachment(int $attachmentId)
    {
        $this->authorizeAccess();
        $attachment = TicketAttachment::where('ticket_id', $this->ticketId)->findOrFail($attachmentId);

        if (! Storage::disk('local')->exists($attachment->file_path)) {
            session()->flash('error', 'File lampiran tidak ditemukan di storage server.');
            return;
        }

        return Storage::disk('local')->download($attachment->file_path, $attachment->original_name);
    }

    public function render()
    {
        $ticket = $this->getTicket();
        $user = Auth::user();

        $itSupportStaff = $user->hasRole('administrator') 
            ? User::role('it_support')->where('is_active', true)->orderBy('name')->get() 
            : collect();

        return view('livewire.tickets.show', [
            'ticket' => $ticket,
            'itSupportStaff' => $itSupportStaff,
            'currentUser' => $user,
        ]);
    }
}
