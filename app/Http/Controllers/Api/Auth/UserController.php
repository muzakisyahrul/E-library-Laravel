<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\User;
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(Request $request){

        $username = $request->username;
        $password = $request->password;
        // return response()->json($request->all());
                
        $field = filter_var($username, FILTER_VALIDATE_EMAIL)? 'email' : 'nip';
        // Auth::attempt password in database must be hashing with bcrypt
        if (Auth::attempt([$field => $username, 'password' => $password])) {
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->accessToken; 
            return response()->json(['success' => $success], $this->successStatus); 
    	}else{
    		return response()->json(['error'=>'Unauthorised'], 401);
    	}

    }


    public function logout()
    {
        Auth::logout();
        return redirect()->Route('login')->with('status','Anda Berhasil Logout.');
    }

    public function get_user()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }
    
}
