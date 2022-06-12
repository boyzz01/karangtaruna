<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    public function Verif($token = null)
    {
    

       $user = User::where('email_token',$token)->first();

       if($user == null ){

       	// session()->flash('message', 'Invalid Login attempt');

       

       }

       $user->update([
        
        'email_verified' => 1,
        'email_verified_at' => Carbon::now(),
        'email_token' => ''

       ]);
       
       //	session()->flash('message', 'Your account is activated, you can log in now');

        

    }
}
