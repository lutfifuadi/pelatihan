<?php

namespace App\Livewire;

use Livewire\Component;

class NavbarComponent extends Component
{
    protected $listeners = ['profile-updated' => '$refresh'];

    public function render()
    {
        return view('livewire.navbar-component');
    }
}
