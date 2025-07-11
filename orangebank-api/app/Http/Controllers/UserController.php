<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function create(Request $request){
        $user = new User;
        $user->name=$request->email;
        $user->email=$request->email;
        $user->CPF=$request->CPF;
        $user->password=$request->password;
        return response()->json([
            'response'=>'usuario criado com sucesso!'
        ]);
    }
}
