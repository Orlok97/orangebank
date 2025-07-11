<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContaCorrente;

class ContaCorrenteController extends Controller
{
    private $saldo=0.0;

    public function depositarSaldo(Request $request, $valor){
        $corrente=ContaCorrente::where('user_id',auth()->user()->id)->first();
        if($valor > 0){
            $corrente->saldo=$corrente->saldo+$valor;
            $corrente->save();
            return response()->json([
            'response'=>'você depositou '.$valor. ' em sua conta corrente'
        ], 200);
        }else{
            return response()->json([
                'response'=>'operação inválida.'
            ]);
        } 

    }
}
