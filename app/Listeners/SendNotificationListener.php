<?php

namespace App\Listeners;

use App\Events\JadwalReminder;
use App\Events\PendaftaranApproved;
use App\Events\PendaftaranRejected;
use App\Events\SertifikatDiterbitkan;
use App\Events\PesertaRegistered;
use App\Events\TugasBaru;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class SendNotificationListener
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function handlePesertaRegistered(PesertaRegistered $event): void
    {
        $data = [
            'nama' => $event->user->name,
            'pelatihan' => $event->pelatihan?->nama ?? 'Aplikasi Pelatihan',
        ];

        $this->notificationService->sendByTemplate(
            $event->user,
            'welcome_peserta',
            $data
        );
    }

    public function handlePendaftaranApproved(PendaftaranApproved $event): void
    {
        $this->notificationService->sendByTemplate(
            $event->user,
            'pendaftaran_diterima',
            [
                'nama' => $event->user->name,
                'pelatihan' => $event->pelatihan->nama,
            ]
        );
    }

    public function handleTugasBaru(TugasBaru $event): void
    {
        $tugasNama = is_object($event->tugas) ? ($event->tugas->nama ?? $event->tugas->judul ?? 'Tugas Baru') : (string) $event->tugas;

        $this->notificationService->sendByTemplate(
            $event->user,
            'tugas_baru',
            [
                'nama' => $event->user->name,
                'tugas' => $tugasNama,
                'pelatihan' => $event->pelatihan->nama,
            ]
        );
    }

    public function handlePendaftaranRejected(PendaftaranRejected $event): void
    {
        $this->notificationService->sendByTemplate(
            $event->user,
            'pendaftaran_ditolak',
            [
                'nama' => $event->user->name,
                'pelatihan' => $event->pelatihan->nama,
                'alasan' => $event->notes ?? '',
            ]
        );
    }

    public function handleSertifikatDiterbitkan(SertifikatDiterbitkan $event): void
    {
        $this->notificationService->sendByTemplate(
            $event->user,
            'sertifikat_terbit',
            [
                'nama' => $event->user->name,
                'pelatihan' => $event->pelatihan->nama,
                'nomor_sertifikat' => $event->certificate->certificate_number,
            ]
        );
    }

    public function handleJadwalReminder(JadwalReminder $event): void
    {
        $tanggal = is_object($event->jadwal) ? ($event->jadwal->tanggal ?? $event->jadwal->waktu ?? now()->toDateString()) : (string) $event->jadwal;

        $this->notificationService->sendByTemplate(
            $event->user,
            'pengingat_jadwal',
            [
                'nama' => $event->user->name,
                'tanggal' => $tanggal,
            ]
        );
    }

    public function subscribe($events): void
    {
        $events->listen(
            PesertaRegistered::class,
            [self::class, 'handlePesertaRegistered']
        );

        $events->listen(
            PendaftaranApproved::class,
            [self::class, 'handlePendaftaranApproved']
        );

        $events->listen(
            TugasBaru::class,
            [self::class, 'handleTugasBaru']
        );

        $events->listen(
            JadwalReminder::class,
            [self::class, 'handleJadwalReminder']
        );

        $events->listen(
            PendaftaranRejected::class,
            [self::class, 'handlePendaftaranRejected']
        );

        $events->listen(
            SertifikatDiterbitkan::class,
            [self::class, 'handleSertifikatDiterbitkan']
        );
    }
}
