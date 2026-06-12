<?php

namespace App\Events;

use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PesertaRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public ?Pelatihan $pelatihan;

    public function __construct(User $user, ?Pelatihan $pelatihan = null)
    {
        $this->user = $user;
        $this->pelatihan = $pelatihan;
    }
}
