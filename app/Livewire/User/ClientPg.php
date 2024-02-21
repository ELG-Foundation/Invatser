<?php

namespace App\Livewire\User;

use App\Enums\country;
use App\Models\UserClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Ramsey\Uuid\Type\Integer;

class ClientPg extends Component
{
    public $count;

    public $client;

    #[Validate('required')]
    public $county = '';

    #[Validate('required')]
    public $name = '';

    #[Validate('required|email')]
    public $email = '';

    #[Validate('required|min:10|max:10')]
    public int $phone;

    #[Validate('required')]
    public $address = '';

    #[Validate('required')]
    public $city = '';

    #[Validate('required|min:6|max:6')]
    public $pincode = '';

    #[Validate('required')]
    public $state = '';

    #[Title('Client')]
    public function render()
    {

        $clinli = UserClient::where('user_id', auth()->user()->id)->paginate(9);

        return view('livewire.user.client-pg', compact('clinli'));
    }

    public function saveclnt()
    {
        $this->validate();

        $data = json_encode([
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'pincode' => $this->pincode,
            'country' => $this->county,
        ]);

        $ud = UserClient::create([
            'user_id' => auth()->user()->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'add_data' => $data,
        ]);

        $this->reset();

        $this->count = 1;
    }

    public function mount()
    {
        $this->count = 1;
    }

    public function addclnt()
    {
        $this->count = 2;
    }

    public function back()
    {
        $this->count = 1;
    }

    public function clntact($id)
    {
        $cid = UserClient::where('id', $id)->latest()->first();

        if ($cid->user_id == auth()->user()->id) {
            if ($cid->active == false) {

                $cid->update([
                    'active' => true,
                ]);

                $this->dispatch('success', title: 'Updated Client Info!');
                
            } else {

                $cid->update([
                    'active' => false,
                ]);

                $this->dispatch('success', title: 'Updated Client Info!');
            }

        } else {
            $this->dispatch('error', title: 'Client Not Found!');
        }
    }
}
