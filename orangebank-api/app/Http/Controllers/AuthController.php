<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
            $token=$request->user()->createToken('token');
            return response()->json([
                'response'=>'usuario autenticado',
                'status'=>'ok',
                'token'=>$token->plainTextToken
            ]);
        }else{
            return response()->json([
                'response'=>'email e/ou senha incorretos'
            ]);
        }
    }
}
