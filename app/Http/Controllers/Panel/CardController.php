<?php

namespace App\Http\Controllers\Panel;

use App\Models\Panel\Bandeira;
use App\Models\Panel\CartaoMovimento;
use App\Models\Panel\CashFlow;
use App\Models\Panel\CashHistory;
use App\Models\Panel\Cashier;
use App\Models\Panel\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Panel\Card;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

class CardController extends Controller
{
    public function index()
    {
        $operadoras = Card::all();

        return view('panel.cartao.index', compact('operadoras'));
    }

    public function create()
    {
        return view('panel.cartao.create');
    }

    public function store(Request $request, Card $card)
    {
        $dataForm = $request->except('_token');

        $insert = $card->create($dataForm);

        if ($insert) {
            return redirect(route('card.index'))->with('success', 'Cadastro realizado com sucesso');
        } else {
            return redirect()->back()->with('error', 'Algo deu errado, tente novamente');
        }
    }

    public function edit($id)
    {
        $card = Card::find($id);

        return view('panel.cartao.edit', compact('card'));
    }

    public function update($id, Request $request)
    {

        $dataForm = $request->except('_token');

        $card = Card::find($id);

        $update = $card->update($dataForm);

        if ($update) {
            return redirect(route('card.index'))->with('sucess', 'Registro alterado com sucesso');
        } else {
            return redirect()->back()->with('error', 'Algo deu errado, tente novamente');
        }

    }

    public function delete($id)
    {

        $delete = Card::destroy($id);

        if ($delete) {
            return redirect(route('card.index'))->with('success', 'Registro apagado com sucesso');
        } else {
            return redirect()->back()->with('error', 'Não foi possível continuar, tente novamente');
        }

    }

    public function bandeiras($id)
    {
        $bandeiras = Card::find($id)->bandeiras;

        $operadora = Card::find($id);

        return view('panel.cartao.bandeira.index', compact('bandeiras', 'operadora'));
    }

    public function createCartaoMovimentoEntrada(Request $request, CashFlow $cashFlow, CashHistory $history)
    {

        $user = Auth::user();

        $dataForm = $request->except('_token');

        $dataForm['valor_pago'] = str_replace(',', 'v', $dataForm['valor_pago']);
        $dataForm['valor_pago'] = str_replace('.', '', $dataForm['valor_pago']);
        $dataForm['valor_pago'] = str_replace('v', '.', $dataForm['valor_pago']);;

        $cashier = Cashier::where('user_id', $user['id'])->first();

        $lastCashFlow = $cashFlow->where('status', 'Aberto')->where('cashier_id', $cashier['id'])->orderBy('created_at', 'desc')->first();

        $cashHistory['cashier_id'] = $cashier['id'];
        $cashHistory['abertura_id'] = $lastCashFlow['id'];
        $cashHistory['referencia'] = $dataForm['referencia'];
        $cashHistory['documento'] = $dataForm['documento'];
        $cashHistory['descricao'] = $dataForm['descricao'];
        $cashHistory['valor'] = $dataForm['valor_pago'];
        $cashHistory['entrada'] = 1;
        $pagTipo = explode(',', $dataForm['pag_tipo']);
        $cashHistory['pag_tipo'] = $pagTipo[1];
        $cashHistory['pag_tipo_categoria'] = $pagTipo[2];

        $invoice['id'] = $dataForm['id'];
        if ($dataForm['valor_pendente'] > $dataForm['valor_pago']) {
            $invoice['quitada'] = 0;
            $invoice['valor_pendente'] = $dataForm['valor_pendente'] - $dataForm['valor_pago'];
        } else {
            $invoice['quitada'] = 1;
            $invoice['valor_pendente'] = 0;
        }

        $request->session()->put('cashHistory', $cashHistory);

        $request->session()->put('invoice', $invoice);

        $bandeiras = Bandeira::all();
        return view('panel.cartao.cartaomovimentos.invoice-receive', compact('dataForm', 'bandeiras'));

    }

    public function storeCartaoMovimentoEntrada(Request $request, Bandeira $bandeira, Invoice $invoiceClass, CashHistory $cashHistoryClass, CartaoMovimento $cartaoMovimentoClass)
    {
        $dataForm = $request->all();

        $cashHistory = $request->session()->get('cashHistory');
        $invoice = $request->session()->get('invoice');
        $invoiceID = $invoice['id'];

        $bandeira = Bandeira::find($dataForm['id_bandeira']);

        $operadora = $bandeira->card;

        $dataForm['valor'] = str_replace('.', '', $dataForm['valor']);
        $dataForm['valor'] = str_replace(',', '.', $dataForm['valor']);


        $valorParcela = $dataForm['valor'] / $dataForm['NParcelas'];

        $valorLiquido = $valorParcela - ($valorParcela * ($bandeira->taxa / 100));

        $now = Date::now();

        $i = 1;

        DB::beginTransaction();

        while ($i <= $dataForm['NParcelas']) {
            $now->add($bandeira->dias . ' days');
            $cartaoMovimento['CodigoOperadora'] = $operadora->id;
            $cartaoMovimento['bandeira_id'] = $bandeira->id;
            $cartaoMovimento['bandeira'] = $bandeira->nome;
            $cartaoMovimento['tipo'] = $bandeira->tipo;
            $cartaoMovimento['entrada'] = 1;
            $cartaoMovimento['cv'] = $dataForm['cv'];
            $cartaoMovimento['NParcelas'] = $dataForm['NParcelas'];
            $cartaoMovimento['parcela'] = $i;
            $cartaoMovimento['valor'] = $valorParcela;
            $cartaoMovimento['taxa'] = $bandeira->taxa;
            $cartaoMovimento['valor_liquido'] = $valorLiquido;
            $cartaoMovimento['previsao'] = $now->format('Y-m-d');
            $cartaoMovimento['liquidado'] = 0;
            $cartaoMovimento['documento'] = $cashHistory['documento'] . '/' . $i;
            $insert = $cartaoMovimentoClass->create($cartaoMovimento);
            $i++;
        }

        $updateInvoice = $invoiceClass->where('id', $invoice['id'])->update($invoice);

        $insertCashHistory = $cashHistoryClass->create($cashHistory);

        if ($updateInvoice && $insertCashHistory && $insert) {
            DB::commit();
            $request->session()->forget('cashHistory');
            $request->session()->forget('invoice');
            return redirect(route('invoice.receive.index'))->with('success', 'Conta baixada com sucesso');
        } else {
            DB::rollBack();
            $request->session()->forget('cashHistory');
            $request->session()->forget('invoice');
            return redirect('receivement.invoice.receive', ['id' => $invoiceID])->with('erro', 'Algo deu errado, tente novamente');
        }

    }

    public function createCartaoMovimentoSaida(Request $request, CashFlow $cashFlow)
    {

        $user = Auth::user();

        $dataForm = $request->except('_token');

        $dataForm['valor_pago'] = str_replace(',', 'v', $dataForm['valor_pago']);
        $dataForm['valor_pago'] = str_replace('.', '', $dataForm['valor_pago']);
        $dataForm['valor_pago'] = str_replace('v', '.', $dataForm['valor_pago']);;

        $cashier = Cashier::where('user_id', $user['id'])->first();

        $lastCashFlow = $cashFlow->where('status', 'Aberto')->where('cashier_id', $cashier['id'])->orderBy('created_at', 'desc')->first();

        $cashHistory['cashier_id'] = $cashier['id'];
        $cashHistory['abertura_id'] = $lastCashFlow['id'];
        $cashHistory['referencia'] = $dataForm['referencia'];
        $cashHistory['documento'] = $dataForm['documento'];
        $cashHistory['descricao'] = $dataForm['descricao'];
        $cashHistory['valor'] = $dataForm['valor_pago'];
        $cashHistory['entrada'] = 0;
        $pagTipo = explode(',', $dataForm['pag_tipo']);
        $cashHistory['pag_tipo'] = $pagTipo[1];
        $cashHistory['pag_tipo_categoria'] = $pagTipo[2];
        $invoiceDB = Invoice::find($dataForm['id']);
        $invoice['id'] = $dataForm['id'];
        if ($dataForm['valor_pendente'] > $dataForm['valor_pago']) {
            $invoice['quitada'] = 0;
            $invoice['valor_pendente'] = $dataForm['valor_pendente'] - $dataForm['valor_pago'];
        } else {
            $invoice['quitada'] = 1;
            $invoice['valor_pendente'] = 0;
        }

        $request->session()->put('cashHistory', $cashHistory);

        $request->session()->put('invoice', $invoice);

        $bandeiras = Bandeira::all();
        return view('panel.cartao.cartaomovimentos.invoice-pay', compact('dataForm', 'bandeiras'));

    }

    public function storeCartaoMovimentoSaida(Request $request, Invoice $invoiceClass, CashHistory $cashHistoryClass, CartaoMovimento $cartaoMovimentoClass)
    {
        $dataForm = $request->except('_token');

        $cashHistory = $request->session()->get('cashHistory');
        $invoice = $request->session()->get('invoice');
        $invoiceID = $invoice['id'];

        $bandeira = Bandeira::find($dataForm['id_bandeira']);

        $operadora = $bandeira->card;

        $dataForm['valor'] = str_replace('.', '', $dataForm['valor']);
        $dataForm['valor'] = str_replace(',', '.', $dataForm['valor']);

        $valorParcela = $dataForm['valor'] / $dataForm['NParcelas'];

        $valorLiquido = $valorParcela - ($valorParcela * ($bandeira->taxa / 100));

        $now = Date::now();

        $i = 1;

        DB::beginTransaction();

        while ($i <= $dataForm['NParcelas']) {
            $now->add($bandeira->dias . ' days');
            $cartaoMovimento['CodigoOperadora'] = $operadora->id;
            $cartaoMovimento['bandeira_id'] = $bandeira->id;
            $cartaoMovimento['bandeira'] = $bandeira->nome;
            $cartaoMovimento['tipo'] = $bandeira->tipo;
            $cartaoMovimento['entrada'] = 0;
            $cartaoMovimento['cv'] = $dataForm['cv'];
            $cartaoMovimento['NParcelas'] = $dataForm['NParcelas'];
            $cartaoMovimento['parcela'] = $i;
            $cartaoMovimento['valor'] = $valorParcela;
            $cartaoMovimento['taxa'] = $bandeira->taxa;
            $cartaoMovimento['valor_liquido'] = $valorLiquido;
            $cartaoMovimento['previsao'] = $now->format('Y-m-d');
            $cartaoMovimento['liquidado'] = 0;
            $cartaoMovimento['documento'] = $cashHistory['documento'] . '/' . $i;
            $insert = $cartaoMovimentoClass->create($cartaoMovimento);
            $i++;
        }

        $updateInvoice = $invoiceClass->where('id', $invoice['id'])->update($invoice);

        $insertCashHistory = $cashHistoryClass->create($cashHistory);

        if ($updateInvoice && $insertCashHistory && $insert) {
            DB::commit();
            $request->session()->forget('cashHistory');
            $request->session()->forget('invoice');
            return redirect(route('invoice.pay.index'))->with('success', 'Conta baixada com sucesso');
        } else {
            DB::rollBack();
            $request->session()->forget('cashHistory');
            $request->session()->forget('invoice');
            return redirect('payment.invoice.pay', ['id' => $invoiceID])->with('erro', 'Algo deu errado, tente novamente');
        }

    }

}


