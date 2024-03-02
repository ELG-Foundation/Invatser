<?php

namespace App\Livewire\User;

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

    public $inputs = [];

    public $i;

    public $js = [];

    public $jsonArray = [];

    public $total;

    public $customer = null;

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

        $this->customer = null;
    }

    public function addinvo()
    {
        $this->count = 2;
    }

    public function back()
    {
        $this->count = 1;
    }

    public function invosav($fields, $balance, $mtotal, $client, $subtotal)
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
        ]);

        $this->dispatch('success', title: 'Invoice Created Successfully!');

        // dd($this->jsonArray, $mtotal, $subtotal, $balance);

        $this->count = 1;
    }

    public function download($id)
    {
        $invo = UserInvoice::where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->first();

        $pay = UserPayment::where('invoice_id', $invo->id)
            ->where('user_id', auth()->user()->id)
            ->get();

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
            $pdf = Pdf::loadView('pdf.invoice', ['users' => $invo, 'paid' => $this->total]);

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
        $count = 2;

        $this->redirect('payment', $navigate = true);
    }

    public function paginationView()
    {
        return 'livewire.user.comp.pagination-user';
    }
}
