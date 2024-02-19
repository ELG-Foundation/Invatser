<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

use function Laravel\Prompts\error;

#[Layout('layouts.dash')]
class LoginPg extends Component
{
    public $email = '';

    public $password = '';

    public $checkbox;

    public $count = 0;

    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login-pg');
    }

    public function save()
    {   
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
            'checkbox' => 'required',
        ]);

        $user = User::where('email', $this->email)->first();

        if ($user != null) {
            if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->checkbox)) {
                session()->regenerate();

                $this->dispatch('success', title: 'Account Login Successfull!');

                return redirect(route('dashboard'));
            } else {
                $this->dispatch('warning', title: 'Email or Password not march');
            }
        } else {

            $this->dispatch('warning', title: 'Account not found!');
        }
    }

    public function mount()
    {
        if (auth()->user() == true) {

            if (auth()->user()->role == 'suadmin') {

                return redirect(route('suadmin.dash'));

            } elseif (auth()->user()->role == 'admin') {

                return redirect(route('admin.dash'));

            } elseif (auth()->user()->role == 'user') {

                return redirect(route('user.dash'));

            } else {

                return view('livewire.auth.login');
            }
        }
    }

    public function logout()
    {
        auth()->logout();
        
        return redirect(route('login'));
    }
}
