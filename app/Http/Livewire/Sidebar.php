<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Sidebar extends Component
{


    // public $isOpen = false;

    // protected $listeners = ['toggleSidebar'];

    // public function toggleSidebar()
    // {
    //     $this->isOpen = !$this->isOpen;
    // }

    public function render()
    {
        return view('livewire.sidebar');
    }
}
