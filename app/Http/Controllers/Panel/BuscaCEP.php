<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BuscaCEP extends Controller
{
    public function buscaCEP(Request $request){
        $cep = str_replace('-', '', $request->cep);
        $cep = str_replace('.', '', $cep);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://viacep.com.br/ws/".$cep."/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        return response()->json(['data'=>$data]);
    }
}
