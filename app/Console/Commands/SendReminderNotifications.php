<?php

namespace App\Console\Commands;

use App\Models\Pelatihan;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendReminderNotifications extends Command
{
    protected $signature = 'notifications:send-reminders';

    protected $description = 'Kirim reminder jadwal pelatihan untuk besok';

    public function handle(NotificationService $notificationService): int
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $pelatihans = Pelatihan::whereDate('tanggal_mulai', $tomorrow)->get();

        if ($pelatihans->isEmpty()) {
            $this->info('Tidak ada pelatihan yang mulai besok.');
            return self::SUCCESS;
        }

        $totalSent = 0;
        $bar = $this->output->createProgressBar($pelatihans->count());
        $bar->start();

        foreach ($pelatihans as $pelatihan) {
            $pesertaProfiles = $pelatihan->pesertaProfiles;

            foreach ($pesertaProfiles as $profile) {
                $user = $profile->user;
                if (!$user) {
                    continue;
                }

                $notification = $notificationService->sendByTemplate(
                    $user,
                    'pengingat_jadwal',
                    [
                        'nama_pelatihan' => $pelatihan->nama,
                        'tanggal_mulai' => $pelatihan->tanggal_mulai->format('d/m/Y'),
                        'nama_peserta' => $profile->nama_lengkap ?? $user->name,
                    ]
                );

                if ($notification) {
                    $totalSent++;
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Reminder terkirim: {$totalSent} notifikasi.");

        return self::SUCCESS;
    }
}
