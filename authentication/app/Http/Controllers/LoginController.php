<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller 
{
    public function show()
    {
        return view("login");
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            "email"=> ["required","email"],
            "password"=> ["required","string"],
            ]);

        if(Auth::attempt($credentials))
        {
            $request->session()->regenerate();

            return redirect("/dashboard");
        }


        return back()->withErrors(["Invalid email or password"])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');

    }

    

}