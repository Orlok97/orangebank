<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContaCorrente;

class ContaCorrenteController extends Controller
{

    public function getSaldo(){
        $corrente=ContaCorrente::where('user_id','=',auth()->user()->id)->first();
        return response()->json([
            'response'=>$corrente->saldo
        ]);
    }

    public function depositarSaldo(Request $request){
        $corrente=ContaCorrente::where('user_id',auth()->user()->id)->first();
        if($corrente == null && $request->valor > 0){
            $corrente=new ContaCorrente;
            $corrente->saldo=$request->valor;
            $corrente->user_id=auth()->user()->id;
            $corrente->save();
            return response()->json([
            'response'=>'você depositou '.$corrente->valor. ' em sua conta corrente',
            'status'=>'ok'
        ], 200);
        }elseif($corrente != null && $request->valor > 0){
            $saldoAtual=$corrente->saldo;
            $corrente->saldo=($request->valor+$saldoAtual);
            $corrente->save();
        }

    }
}
