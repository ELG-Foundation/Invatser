<?php

namespace App\Livewire\User\WP;

use chillerlan\QRCode\QRCode;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Rule;
use Livewire\Component;

class WhatIndex extends Component
{

    public $qrc;

    public $count = '';

    public $luser;

    public function render()
    {
        return view('livewire.user.w-p.what-index');
    }

    public function mount()
    {
        try {
            $client = Http::timeout(10)->get('http://localhost:3000/qr');
            $this->count = 2;
        } catch (\Throwable $th) {
            $this->count = 1;
        }

        if ($this->count != 1) {
            if ($client['status'] == false) {
                if (!is_null($client)) {
                    $this->qrc = (new QRCode())->render($client['qrs']);
                }
            } elseif ($client['status'] == true) {
                $this->count = 1;
            }
        }
    }

    public function codeg() 
    {

        // $dm = Http::post('http://localhost:3000/msg/ennd/number/9363551476')
        $this->mount();
    }
}
