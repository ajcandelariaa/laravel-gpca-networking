<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function loginView(){
        if(Session::has('userType')){
            if(Session::get('userType') == 'gpcaAdmin'){
                return redirect('/admin/dashboard');
            }
        }
        return view('admin.login.login');
    }

    
    // RENDER LOGICS
    public function login(Request $request){
        $request->validate([ 
            'username' => 'required',
            'password' => 'required'
        ]);
        if($request->username == env('ADMIN_USERNAME') && $request->password == env('ADMIN_PASSWORD')){
            $request->session()->put('userType', 'gpcaAdmin');
            return Redirect::to("/admin/dashboard")->withSuccess('Welcome');
        } else {
            return Redirect::to("/admin/login")->withFail('Invalid username & password!');
        }
    }

    public function logout(){
        Session::flush();
        return Redirect::to("/admin/login")->withSuccess('Logged out successfully');
    }
}
