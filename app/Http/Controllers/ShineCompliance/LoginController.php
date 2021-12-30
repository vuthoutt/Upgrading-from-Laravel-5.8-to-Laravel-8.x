<?php

namespace App\Http\Controllers\ShineCompliance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    //
    public function index() {
        return view('shineCompliance.auth.login');
    }
}
