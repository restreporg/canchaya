<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}