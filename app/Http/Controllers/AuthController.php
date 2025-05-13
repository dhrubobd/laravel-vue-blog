<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function checkLogin(Request $request){
        //dd($request->all());
        $email = "admin@admin.com";
        $password = "admin";
        
        if(($email == $request->input('email'))&&($password == $request->input('password'))){
            return  redirect()->back()->with('success','Login successful');
        }else{
            return redirect()->back()->with('error','Invalid credentials');
        }
    }
}
