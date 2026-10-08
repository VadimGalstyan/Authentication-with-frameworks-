<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    public function show()
    {
        return view("register");
    }

    public function store(Request $request)
    {
        

        $user = User::create($request->validated());
        
        event(new \Illuminate\Auth\Events\Registered($user));

        Auth::login($user);

        return redirect('/dashboard');
    }
}