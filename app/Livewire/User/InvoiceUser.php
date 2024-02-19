<?php

namespace App\Livewire\User;

use App\Models\UserCategories;
use App\Models\UserInvoice;
use App\Models\UserProduct;
use Illuminate\Support\Arr;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceUser extends Component
{

    public $count;

    public $inputs = [];

    public $i;

    public $js = [];

    public $jsonArray = [];

    public $total;

    public function addinput($i)
    {
        $this->i = $i + 1;
        array_push($this->inputs, $i);
    }

    public function removeinput($i)
    {
        unset($this->inputs[$i]);
        $this->inputs = array_values($this->inputs);
    }

    public function render()
    {
        $uid = auth()->user()->id;

        $invoice = UserInvoice::where('user_id', $uid)->paginate(7);

        $prodli = UserProduct::where('user_id', $uid)->paginate(7);

        return view('livewire.user.invoice-user', compact('invoice', 'prodli'));
    }

    public function mount()
    {
        $this->count = 1;
    }

    public function addinvo()
    {
        $this->count = 2;
    }

    public function back()
    {
        $this->count = 1;
    }

    public function invosav($fields, $mtotal, $subtotal, $balance)
    {

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

        dd($this->jsonArray);
        
        UserInvoice::create([
            'user_id' => auth()->user()->id,
            'product' =>  $this->jsonArray,
            'subtotal' => $subtotal,
            'mtoal' => $mtotal,
            'balance' => $balance,
        ]);

        $this->dispatch('success', title: 'Invoice Created Successfully!');

        // dd($this->jsonArray, $mtotal, $subtotal, $balance);

        $this->count = 1;
    }

    public function download()
    {
        $invo = UserInvoice::where('user_id', auth()->user()->id)
            ->where('id',  3)
            ->first();
    
        $pdf = Pdf::loadView('pdf.invoice', ['users' => $invo]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'invoice.pdf');
    }
}
