<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContaInvestimento;
use App\Models\ContaCorrente;
use App\Models\Stocks;
use App\Models\ExtratoInvestimento;

class ContaInvestimentoController extends Controller
{
    public function getSaldo(){
        $conta_investimento=ContaInvestimento::where('user_id','=',auth()->user()->id)->first();
        
        if($conta_investimento==null){
            $saldo=0;
        }else{
            $saldo=$conta_investimento->saldo;
        }
        return response()->json([
            'response'=>$saldo
        ]);
    }
    public function comprarAtivos(Request $request){
        $conta_investimento=ContaInvestimento::where('user_id','=',auth()->user()->id)->first();
        $ativo=new Stocks;
        if($conta_investimento->saldo >= $request->price){
            $ativo->name=$request->name;
            $ativo->price=$request->price;
            $ativo->user_id=auth()->user()->id;
            $ativo->conta_investimento_id=$conta_investimento->id;
            $ativo->save();

            $extrato_investimento=new ExtratoInvestimento;
            $extrato_investimento->stock_name=$request->name;
            $extrato_investimento->stock_price=$request->price;
            $extrato_investimento->user_id=auth()->user()->id;
            $extrato_investimento->conta_investimento_id=$conta_investimento->id;
            $extrato_investimento->save();

            $conta_investimento->saldo=($conta_investimento->saldo-$request->price);
            $conta_investimento->save();
            
            return response()->json([
                'response'=>'voce acaba de comprar o ativo '.$request->name .'por '.$request->price
            ]);
        }else{
            return response()->json([
                'response'=>'saldo insuficiente para comprar esse ativo'
            ]);
        }
    }
    public function depositarSaldo(Request $request){
        $conta_investimento=ContaInvestimento::where('user_id',auth()->user()->id)->first();
        $conta_corrente=ContaCorrente::where('user_id',auth()->user()->id)->first();
        if($request->valor > 0){
            if($conta_investimento == null){
                $conta_investimento= new ContaInvestimento;
                $conta_investimento->saldo=$request->valor;
                $conta_investimento->user_id=auth()->user()->id;
                $conta_investimento->save();
            }
            elseif($conta_investimento != null){
                $conta_investimento->saldo=($conta_investimento->saldo+$request->valor);
                $conta_investimento->user_id=auth()->user()->id;
                $conta_investimento->save();
            }

            $conta_corrente->saldo=($conta_corrente->saldo-$request->valor);
            $conta_corrente->save();

            return response()->json([
                'response'=>'voce depositou '.$request->valor.' na sua conta de investimento' 
            ]);
        }else{
            return response()->json([
                'response'=>'saldo inválido pra desposito'
            ]);
        }
    }
}
