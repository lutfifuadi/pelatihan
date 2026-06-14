<?php

namespace App\Events;

use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PendaftaranRejected
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public Pelatihan $pelatihan;
    public ?string $notes;

    public function __construct(User $user, Pelatihan $pelatihan, ?string $notes = null)
    {
        $this->user = $user;
        $this->pelatihan = $pelatihan;
        $this->notes = $notes;
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
