<?php

namespace App\Events;

use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TugasBaru
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public $tugas;
    public Pelatihan $pelatihan;

    public function __construct(User $user, $tugas, Pelatihan $pelatihan)
    {
        $this->user = $user;
        $this->tugas = $tugas;
        $this->pelatihan = $pelatihan;
    }
}
