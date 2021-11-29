<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;
use App\Models\Tenant;

class Register extends Component
{
    /** @var string */
    public $name = '';

    /** @var string */
    public $companyName = '';

    /** @var string */
    public $email = '';

    /** @var string */
    public $password = '';


    public function register()
    {
        
        $this->validate([
            'name' => ['required'],
            'companyName' => ['required','string','unique:tenants,name'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required'],
        ]);

        $tenant = Tenant::create([
            'name' => $this->companyName,
        ]);

        $user = User::create([
            'email' => $this->email,
            'name' => $this->name,
            'role' => 'admin',
            'password' => Hash::make($this->password),
            'tenant_id' => $tenant->id,
        ]);

        event(new Registered($user));

        Auth::login($user, true);

        return redirect()->intended(route('home'));
    }
/*
    public function render()
    {
        return view('livewire.auth.register')->extends('layouts.auth');
    } */
}
