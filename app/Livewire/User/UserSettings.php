<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;

class UserSettings extends Component
{

    public $name = '';

    public $email = '';

    public $phone = '';

    public $usd;

    public function render()
    {
        return view('livewire.user.user-settings');
    }

    public function mount()
    {
        $this->usd = User::find(auth()->user()->id)->get();

        foreach ($this->usd as $item) {
            $this->name = $item->name;
            $this->email = $item->email;
            $this->phone = $item->phone;
        }
    }
}
