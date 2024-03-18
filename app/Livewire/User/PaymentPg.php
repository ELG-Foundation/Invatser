<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Models\UserClient;
use App\Models\UserInvoice;
use App\Models\UserPayment;
use Livewire\Attributes\On;
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

    public function dehydrate()
    {
        if ($this->invoid != null) {
            $invoice = UserInvoice::Where('id', $this->invoid)->first();
    
            if ($invoice) {
                $this->amoutn = $invoice->mtoal;
            } else {
                $this->amoutn = 0;
            }
        }
    }

    public function addpay()
    {
        $this->count = 2;

        $this->invoice = UserInvoice::where('user_id', auth()->user()->id)->orderBy('created_at')->get();;
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

        $invoice = UserInvoice::Where('id', $this->invoid)->first();

        

        if (!is_null($invoice->paid)) {
            $nal2 = $invoice->paid + $this->amoutn;
            $nall = $invoice->total - $nal2;
            $tpaid = $nal2;
        } else {
            $nall = $invoice->mtoal - $this->amoutn;
            $tpaid = $this->amoutn;
        }
        
        $invoice->update([
            'mtoal' => $nall,
            'paid' => $tpaid,
        ]);

        $this->dispatch('success', title: 'Payment Added Successfull');

        $this->count = 1;
    }

    public function delete($id)
    {
        $uinvo = UserPayment::where('id', $id)->first();
        $invoice = UserInvoice::Where('id', $uinvo->invoice_id)->first();

        if ($uinvo != null) {
            if (!is_null($invoice->paid)) {
                $nam = $invoice->paid - $uinvo->amount;
                $nam2 = $invoice->mtoal - $uinvo->amount;

                $invoice->update([
                    'mtoal' => $nam2,
                    'paid' => $nam,
                ]);
            }
            UserPayment::find($id)->delete();
            $this->dispatch('success', title: 'Invoice Deleted Successfully!');
        } else {
            $this->dispatch('warning', title: 'Invoice Not Found!');
        }
    }
}