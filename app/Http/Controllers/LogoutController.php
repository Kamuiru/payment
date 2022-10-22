<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function index(){
        auth()->logout();
        return redirect('/login');
    }
}
