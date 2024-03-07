<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UserSettings extends Component
{
    public string $name = '';

    public string $email = '';

    public $phone = '';

    public string $company = '';

    public string $street = '';

    public string $city = '';

    public string $country = '';

    public string $zip = '';

    public string $timezone = '';

    public string $currency = '';

    public bool $deskn = false;

    public bool $emailn = false;

    public bool $chatn = false;

    public $usd;

    public function render()
    {
        return view('livewire.user.user-settings');
    }

    public function mount()
    {
        $this->usd = User::findOrFail(auth()->user()->id);

        if (!is_null($this->usd->address)) {
            $address = json_decode($this->usd->address, true);
        }

        $this->name = $this->usd->name;
        $this->email = $this->usd->email;
        $this->phone = $this->usd->phone ?? '';
        $this->company = $this->usd->company ?? '';
        $this->street = $address['street'] ?? '';
        $this->city = $address['city'] ?? '';
        $this->zip = $address['zip'] ?? '';
        $this->country = $address['country'];
    }

    public function basic()
    {

        $validated = $this->validate([
            'street' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'zip' => 'nullable|min:6|max:6',
            'name' => 'nullable|string',
            'email' => 'nullable|string|email',
            'phone' => 'nullable|min:10|max:10',
            'company' => 'nullable|string',
        ]);

        $addrs = json_encode([
            'street' => $this->street,
            'city' => $this->city,
            'country' => $this->country,
            'zip' => $this->zip,
        ], true);

        $this->usd->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'address' => $addrs,
        ]);
    }

    public function cancle()
    {
        $this->reset();
        
        $this->mount();
    }
}
