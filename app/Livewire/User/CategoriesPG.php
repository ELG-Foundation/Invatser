<?php

namespace App\Livewire\User;

use App\Models\UserCategories;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesPG extends Component
{
    use WithPagination;

    #[Rule('required')]
    public $cate = '';

    #[Title('Categories')]
    public function render()
    {
        $uid = auth()->user()->id;

        $cateli = UserCategories::where('user_id', $uid)->paginate(9);

        return view('livewire.user.categories-p-g', compact('cateli'));
    }

    public function catsave()
    {
        UserCategories::create([
            'user_id' => auth()->user()->id,
            'cate' => $this->cate,
        ]);

        $this->dispatch('success', title: 'Category Created Successfull!');
    }

    public function delete($id)
    {
        $uid = auth()->user()->id;

        $cate = UserCategories::find($id);

        if ($uid == $cate->user_id) {

            $cate->delete();

            $this->dispatch('success', title: 'Category Deleted Successfull!');

        } else {

            $this->dispatch('warning', title: 'Unauthrized Request Found!');
        }
    }

    public function paginationView()
    {
        return 'livewire.user.comp.pagination-user';
    }
}
