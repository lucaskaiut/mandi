<?php

namespace App\Http\Controllers\Panel;

use App\Models\Panel\BankAccount;
use App\Models\Panel\CashFlow;
use App\Models\Panel\CashHistory;
use App\Models\Panel\Cashier;
use App\Models\Panel\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Panel\CartaoMovimento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use App\Models\Panel\Mensalidade;

class CartaoMovimentoController extends Controller
{
    public function index(CartaoMovimento $cartaoMovimento)
    {
        $cartaoMovimentos = $cartaoMovimento->where('liquidado', 0)->paginate(15);

        return view('panel.cartao.cartaomovimentos.index', compact('cartaoMovimentos'));
    }

    public function baixar($id)
    {
        $bankAccounts = BankAccount::all();

        return view('panel.cartao.cartaomovimentos.baixar-conta-corrente', compact('id', 'bankAccounts'));
    }

    public function baixarStore($id, Request $request, CashFlow $cashFlow, CashHistory $history)
    {
        $user = Auth::user();

        $dataForm = $request->except('_token');

        $cashier = Cashier::where('user_id', $user->id)->first();

        $lastCashFlow = $cashFlow->where('status', 'Aberto')
            ->where('cashier_id', $cashier->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $cartaoMovimento = CartaoMovimento::find($id);

        $conta = BankAccount::find($dataForm['conta_id']);

        if ($cartaoMovimento->entrada = 1) {
            $contaCorrente['total_amount'] = $conta['total_amount'] + $cartaoMovimento->valor_liquido;
        } else {
            $contaCorrente['total_amount'] = $conta['total_amount'] - $cartaoMovimento->valor_liquido;
        }

        $creditoDebito = $cartaoMovimento->tipo;
        if ($creditoDebito == 'credito') {
            $creditoDebito = 'Crédito';
        } else {
            $creditoDebito = 'Débito';
        }

        $cashHistory['cashier_id'] = $cashier->id;
        $cashHistory['abertura_id'] = $lastCashFlow->id;
        $cashHistory['referencia'] = $cartaoMovimento->cv;
        $cashHistory['documento'] = $cartaoMovimento->documento;
        $cashHistory['descricao'] = 'Baixa Cartão de ' . $creditoDebito . ' NºDoc ' . $cartaoMovimento->documento;
        $cashHistory['valor'] = $cartaoMovimento->valor_liquido;
        $cashHistory['entrada'] = $cartaoMovimento->entrada;
        $cashHistory['pag_tipo'] = 'Bancária';
        $cashHistory['pag_tipo_categoria'] = 'bancaria';

        $movimento['liquidado'] = 1;
        $movimento['dataliquidado'] = Date::now()->format('Y-m-d');

        DB::beginTransaction();

        $updateCC = $conta->update($contaCorrente);

        $updateMovimento = $cartaoMovimento->update($movimento);

        $insertHistory = $history->create($cashHistory);

        if ($updateCC && $updateMovimento && $insertHistory) {
            DB::commit();
            return redirect(route('cartao.movimentos.index'))->with('success', 'Cartão baixado com sucesso');
        } else {
            DB::rollBack();
            return redirect()->back()->with('error', 'Algo deu errado, tente novamente.');
        }

    }

    public function search(Request $request, CartaoMovimento $cartaoMovimento)
    {

        $dataForm = $request->except('_token');

        $cartaoMovimentos = $cartaoMovimento->search($dataForm);

        return view('panel.cartao.cartaomovimentos.index', compact('cartaoMovimentos', 'dataForm'));
    }

    public function estornar($id, Invoice $invoice)
    {
        $cartaoMovimento = CartaoMovimento::find($id);

        $documento = explode('/', $cartaoMovimento['documento']);

        if ($cartaoMovimento->liquidado == 1) {
            $bankAccounts = BankAccount::all();
            return view('panel.cartao.cartaomovimentos.estornar-conta-corrente', compact('id', 'bankAccounts'));
        } else {
            if ($cartaoMovimento->recibo == 1) {
                $mensalidade = Mensalidade::where('recibo', $documento[0])->first();
                if ($mensalidade->amount > $cartaoMovimento->valor) {
                    $mensalidadePendente['descricao'] = 'Estorno de cartao da mensalidade Nº ' . $mensalidade->recibo;
                    $mensalidadePendente['documento'] = $mensalidade->recibo;
                    $mensalidadePendente['valor_original'] = $mensalidade->amount;
                    $mensalidadePendente['valor_pendente'] = $cartaoMovimento->valor;
                    $mensalidadePendente['areceber'] = 1;
                    $mensalidadePendente['vencimento'] = $mensalidade->pagamento;
                    $mensalidadePendente['quitada'] = 0;
                    $mensalidadePendente['fornecedor_id'] = $mensalidade->athlete_id;
                    $mensalidadePendente['razao_social'] = $mensalidade->atleta;
                    $invoice->create($mensalidadePendente);
                }
            } else {
                $invoice = Invoice::where('documento', $documento[0])->first();
                $updateInvoice['quitada'] = 0;
                $updateInvoice['valor_pendente'] = $invoice['valor_pendente'] + $cartaoMovimento['valor'];
                $invoiceUpdate = $invoice->update($updateInvoice);
            }

            $delete = $cartaoMovimento->delete();

        }
        return redirect(route('cartao.movimentos.index'))->with('success', 'Cartão estornado com sucesso');
    }

    public function estornarStore($id, Request $request, Invoice $invoice)
    {
        $dataForm = $request->except('_token');

        $cartaoMovimento = CartaoMovimento::find($id);

        $documento = explode('/', $cartaoMovimento['documento']);

        if ($cartaoMovimento->recibo == 1) {
            $mensalidade = Mensalidade::where('recibo', $documento[0])->first();
            if ($mensalidade->amount > $cartaoMovimento->valor) {
                $mensalidadePendente['descricao'] = 'Estorno de cartao da mensalidade Nº ' . $mensalidade->recibo;
                $mensalidadePendente['documento'] = $mensalidade->recibo;
                $mensalidadePendente['valor_original'] = $mensalidade->amount;
                $mensalidadePendente['valor_pendente'] = $cartaoMovimento->valor;
                $mensalidadePendente['areceber'] = 1;
                $mensalidadePendente['vencimento'] = $mensalidade->pagamento;
                $mensalidadePendente['quitada'] = 0;
                $mensalidadePendente['fornecedor_id'] = $mensalidade->athlete_id;
                $mensalidadePendente['razao_social'] = $mensalidade->atleta;
                $invoice->create($mensalidadePendente);
            }
        } else {
            $invoice = Invoice::where('documento', $documento[0])->first();
            $updateInvoice['quitada'] = 0;
            $updateInvoice['valor_pendente'] = $invoice['valor_pendente'] + $cartaoMovimento['valor'];
            $invoice->update($updateInvoice);
        }

        $cartaoMovimento->delete();

        return redirect(route('cartao.movimentos.index'))->with('success', 'Cartão estonardo com sucesso');
    }

}
