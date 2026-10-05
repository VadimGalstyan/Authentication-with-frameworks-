<?php 
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Class DashboardController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        return view("dashboard", ["user"=> $user]);
    }
}