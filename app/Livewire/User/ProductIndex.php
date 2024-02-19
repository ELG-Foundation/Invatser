<?php

namespace App\Livewire\User;

use App\Models\UserCategories;
use App\Models\UserProduct;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class ProductIndex extends Component
{
    use WithPagination;

    public $count;

    #[Rule('required')]
    public $pname = '';

    #[Rule('required')]
    public $pcode = '';
    
    #[Rule('required')]
    public $pdesc = '';

    #[Rule('required')]
    public $pstat;

    #[Rule('required')]
    public $pcate;

    #[Rule('nullable')]
    public $pvend;

    #[Rule('required')]
    public $ppric = '';
    
    public $pccode = '';

    // public $cateli;

    #[Title('Product')]
    public function render()
    {
        $uid = auth()->user()->id;

        $prodli = UserProduct::where('user_id', $uid)->paginate(9);

        $cateli = UserCategories::where('user_id', $uid)->get();


        return view('livewire.user.product-index', compact('prodli', 'cateli'));
    }

    public function mount()
    {
        $this->count = 1;

        $this->pccode;

        $uid = auth()->user()->id;

       // $this->cateli = UserCategories::where('user_id', $uid)->get();
    }

    public function addprod()
    {
        $this->count = 2;
    }

    public function back()
    {
        $this->count = 1;
    }

    public function prodsav()
    {
        $this->pcode = $this->pccode;;
        $this->validate([
            'pname' => 'required',
            'pcode' => 'required',
            'pdesc' => 'required',
            'pcate' => 'required',
            'pstat' => 'required',
            'ppric' => 'required',
            'pvend' => 'required',
        ]);

        $dat['stat'] = $this->pstat;
        $dat['vend'] = $this->pvend;
        $dat['cate'] = $this->pcate;

        $det = json_encode($dat);

        UserProduct::create([
            'user_id' => auth()->user()->id,
            'name' => $this->pname,
            'code' => $this->pccode,
            'desc' => $this->pdesc,
            'price' => $this->ppric,
            'detail' => $det,
        ]);

        $this->dispatch('success', title: 'Product Created Successfull');

        $this->reset();

        $this->count = 1;
    }

    public function pcde()
    {
        $ecode = Str::random(6);

        $this->pccode = $ecode;
    }

    public function delete($id)
    {
        $uid = auth()->user()->id;

        $prod = UserProduct::find($id);

        if ($uid == $prod->user_id) {
            
            $prod->delete();

            $this->dispatch('success', title: 'Product Deleted Successfull!');
        } else {
            $this->dispatch('warning', title: 'Unauthrized Request Found!');
        }
    }

    public function paginationView()
    {
        return 'livewire.user.comp.pagination-user';
    }
}