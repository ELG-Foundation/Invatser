<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Models\UserCategories;
use App\Models\UserClient;
use App\Models\UserInvoice;
use App\Models\UserPayment;
use App\Models\UserProduct;
use Illuminate\Support\Arr;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class InvoiceUser extends Component
{
    use WithPagination;

    public $count;

    public $cus;

    public $i;

    public $js = [];

    public $jsonArray = [];

    public $total;

    public $customer;

    public $balan;

    #[Title('Invoice')]
    public function render()
    {
        $uid = auth()->user()->id;

        return view('livewire.user.invoice-user', [
            'invoice' => UserInvoice::where('user_id', $uid)->paginate(7),
            'prodli' => UserProduct::where('user_id', $uid)->paginate(7),
            'clntli' => UserClient::where('user_id', $uid)->get(),
            'cltdat' => UserClient::where('id', $this->customer)->first(),
        ]);
    }

    public function mount()
    {
        $this->count = 1;

        if ($this->i != null) {
            $this->cus = UserInvoice::where('id', $this->i)->latest()->first();
            
            if ($this->cus != null) {
                $this->customer = json_decode($this->cus, true);
            }
        }
    }

    public function addinvo()
    {
        $this->count = 2;
    }

    public function back()
    {
        $this->count = 1;
    }

    public function invosav($fields, $balance, $mtotal, $client, $subtotal, $ddate)
    {
        if ($this->count == 2) {
            for ($i = 0; $i < count($fields); $i++) {

                $ipt1 =  $fields[$i]['txt1'];
                $ipt2 =  $fields[$i]['txt2'];
                $ipt3 =  $fields[$i]['txt3'];
                $total = $fields[$i]['total'];
    
                $js = ([
                    'product' => $ipt3,
                    'price' => $ipt1,
                    'quantity' => $ipt2,
                    'total' => $total,
                ]);
    
                $jsonArray[] = $js;
            }
    
            $json = json_encode($jsonArray);
    
            $this->jsonArray = $json;
            // dd($client);
    
            // $val = validator([
            //     'subtotal' => 'required',
            //     'mtoal' => 'required',
            //     'client' => 'required',
            // ]);
    
            // dd($subtotal, $mtotal, $client);
    
            UserInvoice::create([
                'user_id' => auth()->user()->id,
                'client_id' => $client,
                'product' =>  $this->jsonArray,
                'subtotal' => $subtotal,
                'mtoal' => $mtotal,
                'balance' => $balance,
                'due_date' => $ddate,
            ]);
    
            $this->dispatch('success', title: 'Invoice Created Successfully!');
    
            // dd($this->jsonArray, $mtotal, $subtotal, $balance);
    
            $this->count = 1;
        } else {
            $this->dispatch('error', title: 'Unexpected Error!');
        }
    }

    public function invoupd($efield, $balance, $mtotal, $client, $subtotal, $invot, $ddate)
    {
        if ($this->count == 3) {

            for ($i = 0; $i < count($efield); $i++) {

                $ipt1 =  $efield[$i]['txt1'];
                $ipt2 =  $efield[$i]['txt2'];
                $ipt3 =  $efield[$i]['txt3'];
                $total = $efield[$i]['total'];
    
                $js = ([
                    'product' => $ipt3,
                    'price' => $ipt1,
                    'quantity' => $ipt2,
                    'total' => $total,
                ]);
    
                $jsonArray[] = $js;
            }
    
            $json = json_encode($jsonArray);
    
            $this->jsonArray = $json;

            $this->cus->update([
                'product' =>  $this->jsonArray,
                'subtotal' => $subtotal,
                'mtoal' => $mtotal,
                'balance' => $balance,
                'due_date' => $ddate,
            ]);
    
            $this->dispatch('success', title: 'Invoice Updated Successfully!');
    
            $this->count = 1;
        } else {
            $this->dispatch('error', title: 'Unexpected Error!');
        }
    }

    public function download($id)
    {
        $invo = UserInvoice::where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->first();

        $pay = UserPayment::where('invoice_id', $invo->id)
            ->where('user_id', auth()->user()->id)
            ->get();

        $clint = UserClient::where('id', $invo->client_id)
            ->where('user_id', auth()->user()->id)
            ->first();

        $used = User::where('id', auth()->user()->id)
            ->first();

        if ($pay->count() <= 1) {
            foreach ($pay as $value) {
                $this->total = $value->amount;
            }
        } else {
            foreach ($pay as $value) {
                $this->total += $value->amount;
            }
        }

        if ($invo != null) {
            $pdf = Pdf::loadView('pdf.invoice', ['users' => $invo, 'paid' => $this->total, 'client' => $clint, 'admin' => $used]);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'invoice.pdf');
        } else {
            $this->dispatch('warning', title: 'Invoice Not Found!');
        }
    }

    public function delete($id)
    {
        $uinvo = UserInvoice::where('id', $id)->get();

        if ($uinvo != null) {
            UserInvoice::find($id)->delete();
            $this->dispatch('success', title: 'Invoice Deleted Successfully!');
        } else {
            $this->dispatch('warning', title: 'Invoice Not Found!');
        }
    }

    public function edit($id)
    {

        $this->i = $id;

        $this->mount();

        $this->count = 3;
    }

    public function balance($id)
    {   
        if ($id != "") {
            $usrm = UserInvoice::where('client_id', $id)->first();

            if (!is_null($usrm)) {
                $this->balan = $usrm->mtoal;
            } else {
                $this->dispatch('error', title: 'Unexpeted Error!');
            }
        } else {
            $this->balan = null;
        }
    }

    public function paginationView()
    {
        return 'livewire.user.comp.pagination-user';
    }
}
