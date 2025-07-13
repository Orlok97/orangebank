<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExtratoCorrente;

class ExtratoCorrenteController extends Controller
{
    public function listExtrato(){
        $extrato=ExtratoCorrente::where('user_id',auth()->user()->id)->get();
        return response()->json([
            'response'=>$extrato
        ]);
    }
}
