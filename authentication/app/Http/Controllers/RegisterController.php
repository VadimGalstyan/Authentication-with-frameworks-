<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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

        User::create([
            'name'=> $validated['name'],
            'email'=> $validated['email'],
            'password'=> bcrypt($validated['password']),
            ]);

        return redirect('/login');
    }
}