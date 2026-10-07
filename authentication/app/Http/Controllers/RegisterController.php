<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class RegisterController extends Controller
{

    public function show()
    {
        return view("register");
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name'=>['required','string','max:255'],
            'email'=>['required','string','unique:users,email'],
            'password'=>['required','string','min:8','confirmed'],
            ]);

        $user = User::create([
            'name'=> $validated['name'],
            'email'=> $validated['email'],
            'password'=> bcrypt($validated['password']),
            ]);
        
        event(new \Illuminate\Auth\Events\Registered($user));

        Auth::login($user);

        return redirect('/dashboard');
    }
}