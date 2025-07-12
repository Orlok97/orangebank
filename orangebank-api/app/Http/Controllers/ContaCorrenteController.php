<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContaCorrente;
use App\Models\User;
use App\Models\ExtratoCorrente;

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

    public function transferirSaldo(Request $request, $id){
        $user=User::find($id);
        if(!$user){
            return response()->json([
                'usuario não encontrado'
            ]);
        }
        $conta_corrente=ContaCorrente::where('user_id',$user->id)->first();
        if($conta_corrente == null && $request->valor > 0){
            $conta_corrente=new ContaCorrente;
            $conta_corrente->saldo=$request->valor;
            $conta_corrente->user_id=$user->id;
            $conta_corrente->save();
        }elseif($conta_corrente->saldo != null && $request->valor > 0){
            $saldoAtual=$conta_corrente->salto;;
            $conta_corrente->saldo=($request->valor+$saldoAtual);
            $conta_corrente->save();
        }
        $extrato=new ExtratoCorrente;
        $extrato->saldo=$request->valor;
        $extrato->user_id=auth()->user()->id;
        $extrato->receiver_id=$user->id;
        $extrato->save();
        return response()->json([
            'response'=>'vc transfiriu '.$request->valor.' para '. $user->name
        ]);
    }
}
