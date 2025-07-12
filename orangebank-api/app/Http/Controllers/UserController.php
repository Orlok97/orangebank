<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ContaCorrente;

class UserController extends Controller
{
    public function index(){
        $users=User::all();
        return response()->json([
            'response'=>$users
        ]);
    }
    public function getAuthUser(){
        $user=auth()->user();
        return response()->json([
            'response'=>$user
        ]);
    }
    public function create(Request $request){
        $user = new User;
        // dd($request->all())
        $user->name=$request->name;
        $user->email=$request->email;
        $user->birthDate=$request->birthDate;
        $user->CPF=$request->CPF;
        $user->password=$request->password;
        $user->save();
        return response()->json([
            'response'=>'usuario criado com sucesso!'
        ]);
    }
}
