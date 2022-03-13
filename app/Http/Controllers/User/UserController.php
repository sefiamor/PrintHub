<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function create(Request $request)
    {
        # validating input
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|max:30',
            'cpassword' => 'required|min:5|max:30|same:password'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->name);
        $save = $user->save();

        if($save){
            return redirect()->back()->with('success', 'You are now registed successfully');
        }else{
            return redirect()->back()->with('fail', 'Something went wrong, failed to register');
        }
    }

    public function check(Request $request)
    {
        # validate input
        $request->validate([
            'email' =>'required|email|exists:users,email',
            'password' =>'required|min:5|max:30'
        ],[
            'email.exists' => 'Sorry this email is invalid.'
        ]);

        $creds =$request->only('email','password');
        if(Auth::attempt($creds)){
            return redirect()->route('user.home');
        }else{
            return redirect()->route('user.login')->with('fail','Incorrect credentials');
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
