<?php

namespace App\Livewire\User;

use App\Models\UserClient;
use App\Models\UserInvoice;
use App\Models\UserPayment;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Symfony\Contracts\Service\Attribute\Required;

class PaymentPg extends Component
{
    public $count;

    public $invoice;

    #[Rule('required')]
    public $amoutn = '';

    #[Rule('required')]
    public $date = '';

    #[Rule('nullable')]
    public $note = '';

    #[Rule('required')]
    public $invoid = '';

    #[Rule('required')]
    public $stat = '';

    #[Rule('required')]
    public $mode = '';



    public function render()
    {
        return view('livewire.user.payment-pg', [
            'payment' => UserPayment::with('client')
            ->where('user_id', auth()->user()->id)
            ->paginate(7),
            'client' => UserClient::where('user_id', auth()->user()->id)
        ]);
    }

    public function mount()
    {
        $this->count = 1;
    }

    public function addpay()
    {
        $this->count = 2;

        $this->invoice = UserInvoice::where('user_id', auth()->user()->id)->latest()->first();
    }

    public function back()
    {
        $this->count = 1;     
    }

    public function savepay()
    {
        $this->validate();
        
        UserPayment::Create([
            'user_id' => auth()->user()->id,
            'amount' => $this->amoutn,
            'date' => $this->date,
            'note' => $this->note,
            'invoice_id' => $this->invoid,
            'status' => $this->stat,
            'mode' => $this->mode,
        ]);

        $iuid = UserInvoice::where('id', $this->invoid)->latest()->first();

        if ($iuid->user_id == auth()->user()->id) {

            $mtal['mtoal'] = $iuid->mtoal - $this->amoutn;

            $iuid->update($mtal);

        } else {
            $this->dispatch('error', title: 'Unexpected Error');
        }

        $this->dispatch('success', title: 'Payment Added Successfull');
    }
}
