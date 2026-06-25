<?php

namespace App\Livewire\Peserta;

use App\Models\Enrollment;
use App\Models\WhatsappNumber;
use Livewire\Component;

class WaitingConfirmation extends Component
{
    public Enrollment $enrollment;

    public function mount(): void
    {
        $this->enrollment = auth()->user()->enrollments()
            ->where('status', 'approved')
            ->whereNotNull('verification_code')
            ->whereNull('wa_confirmed_at')
            ->latest()
            ->firstOrFail();
    }

    public function getWaLinkProperty(): string
    {
        $user = $this->enrollment->user;
        $pelatihan = $this->enrollment->pelatihan;
        $dinas = $pelatihan->dinas;

        $message = "Halo Admin, saya ingin konfirmasi pendaftaran pelatihan.\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━\n";
        $message .= "📋 DATA PESERTA\n";
        $message .= "━━━━━━━━━━━━━━━━━━━\n";
        $message .= "Nama Lengkap    : {$user->name}\n";
        $message .= "NIK             : {$user->nik}\n";
        $message .= "WA Terdaftar    : {$user->whatsapp}\n";
        $message .= "Kelurahan       : {$user->kelurahan?->name}\n";
        $message .= "Kecamatan       : {$user->kecamatan?->name}\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━\n";
        $message .= "🎓 PELATIHAN\n";
        $message .= "━━━━━━━━━━━━━━━━━━━\n";
        $message .= "Nama Pelatihan  : {$pelatihan->nama}\n";
        $message .= "Tanggal         : {$pelatihan->tanggal_mulai->format('j F Y')} s.d. {$pelatihan->tanggal_selesai->format('j F Y')}\n";
        $message .= "Dinas/Instansi  : {$dinas?->nama_dinas}\n\n";
        $message .= "━━━━━━━━━━━━━━━━━━━\n";
        $message .= "🔑 Kode Verifikasi: {$this->enrollment->verification_code}\n";
        $message .= "━━━━━━━━━━━━━━━━━━━\n\n";
        $message .= "Saya telah menyetujui syarat dan ketentuan yang berlaku.";

        // Ambil nomor admin dari tabel whatsapp_numbers (fitur layanan support)
        $adminNumber = WhatsappNumber::active()->sorted()->first()?->number ?? '6280000000000';

        return "https://wa.me/{$adminNumber}?text=" . urlencode($message);
    }

    public function render()
    {
        return view('livewire.peserta.waiting-confirmation');
    }
}
