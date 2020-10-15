<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Panel\Bandeira;
use App\Models\Panel\Card;

class BandeiraController extends Controller
{

    public function store($id, Request $request,Bandeira $bandeira)
    {
        $dataForm = $request->except('_token');

        $dataForm['card_id'] = $id;

        $dataForm['taxa'] = str_replace(',','.',$dataForm['taxa']);

        $insert = $bandeira->create($dataForm);

        if($insert){
            return redirect(route('card.bandeiras', ['id' => $id]))->with('success', 'Bandeira adicionada com sucesso');
        } else {
            return redirect()->back()->with('error', 'Algo deu errado, tente novamente');
        }
    }

    public function edit($id)
    {
        $bandeira = Bandeira::find($id);

        $bandeiras = Card::find($bandeira->card_id)->bandeiras;

        $operadora = Card::find($bandeira->card_id);

        return view('panel.cartao.bandeira.edit', compact('bandeira', 'operadora', 'bandeiras'));
    }

    public function update($id, Request $request)
    {
        $dataForm = $request->except('_token');

        $dataForm['taxa'] = str_replace(',','.',$dataForm['taxa']);

        $bandeira = Bandeira::find($id);

        $operadora = Card::find($bandeira->card_id);

        $update = $bandeira->update($dataForm);

        if($update){
            return redirect(route('card.bandeiras', ['id' => $operadora->id]))->with('success', 'Registro alterado com sucesso');
        } else {
            return redirect()->back()->with('error', 'Algo deu errado, tente novamente');
        }
    }

    public function delete($id)
    {
        $bandeira = Bandeira::find($id);

        $operadora = Card::find($bandeira->card_id);

        $delete = Bandeira::destroy($id);

        if($delete){
            return redirect(route('card.bandeiras', ['id' => $operadora->id]))->with('success', 'Registro apagado com sucesso');
        } else {
            return redirect()->back()->with('error', 'Algo deu errado, tente novamente');
        }
    }

}


