<?php

namespace App\Events;

use App\Models\Certificate;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SertifikatDiterbitkan
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public Pelatihan $pelatihan;
    public Certificate $certificate;

    public function __construct(User $user, Pelatihan $pelatihan, Certificate $certificate)
    {
        $this->user = $user;
        $this->pelatihan = $pelatihan;
        $this->certificate = $certificate;
    }
}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
