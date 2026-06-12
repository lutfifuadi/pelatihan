<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JadwalReminder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public $jadwal;

    public function __construct(User $user, $jadwal)
    {
        $this->user = $user;
        $this->jadwal = $jadwal;
    }
}
