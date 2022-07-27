<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;

class LoginController extends Controller
{
    //

    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {

        $errors = new MessageBag;
        $credential = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = Admin::where('username', $request->username)->first();
        // Attempt to log the user in
        if ($user == null) {
            return redirect()->back()->withInput($request->only('email', 'remember'))->with('error', 'Wrong Username or Password.');
        }

        if (Auth::attempt($credential)) {

            $request->session()->regenerate(); //me-generate ulang session untuk menghindari hacking

            return redirect()->intended('/');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->with('error', 'Wrong Username or Password.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
