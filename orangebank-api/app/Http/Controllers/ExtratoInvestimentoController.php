<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExtratoInvestimento;
use App\Models\Stocks;

class ExtratoInvestimentoController extends Controller
{
    public function listExtrato(){
        $extrato=ExtratoInvestimento::where('user_id',auth()->user()->id)->get();
        return response()->json([
            'response'=>$extrato
        ]);
    }
}
