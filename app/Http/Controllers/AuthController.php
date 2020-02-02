<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;
use App\User;

class AuthController extends Controller
{
    public function p_login(Request $request){

        $username = $request->username;
        $password = $request->password;

        if(empty($username)){
            return redirect()->Route('login')->with('status','Email / Nip tidak boleh kosong.');
        }elseif(empty($password)){
            return redirect()->Route('login')->with('status','Password tidak boleh kosong.');
        }
        
        $field = filter_var($username, FILTER_VALIDATE_EMAIL)? 'email' : 'nip';
      
        // Auth::attempt password in database must be hashing with bcrypt
        if (Auth::attempt([$field => $username, 'password' => $password])) {
            return redirect()->Route('dashboard');
    	}else{
    		return redirect()->Route('login')->with('status','Usename / Password Yang Ada Masukkan Salah.');
    	}

    }


    public function logout()
    {
        Auth::logout();
        return redirect()->Route('login')->with('status','Anda Berhasil Logout.');
    }
    
}
