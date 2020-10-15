<?php

namespace App\Http\Controllers\Panel;

use App\Models\Panel\CashFlow;
use App\Models\Panel\Customer;
use Illuminate\Support\Facades\Auth;
use App\Models\Panel\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Panel\Invoice;
use App\Models\Panel\Cashier;
use App\Models\Panel\CashHistory;
use App\Models\Panel\BankAccount;
use Carbon\Carbon;
use Jenssegers\Date\Date;
use App\Models\Panel\Bandeira;

class InvoiceController extends Controller
{

    private $totalPaginate = 15;

    public function indexPay(Invoice $invoice)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $today = date('Y-m-d');

        $contaVencidas = $invoice->where('vencimento', '<', $today)->where('areceber', 0)->where('quitada', 0)->get();

        $contasVencidas = count($contaVencidas);

        $total = DB::table('invoices')
            ->where('quitada', '=', 0)
            ->where('areceber', '=', 0)
            ->sum('valor_original');

        $totalVencidas = $contaVencidas->sum('valor_pendente');

        $quitadas = DB::select('SELECT (sum(valor_original))-(sum(valor_pendente)) as  totalQuitadas FROM `invoices`');

        $totalQuitadas = $quitadas[0]->totalQuitadas;

        //dd($totalQuitadas);

        $invoices = $invoice->where('areceber', 0)->where('quitada', 0)->paginate($this->totalPaginate);

        $totalRegister = count($invoices);

        $quitada = 0;

        return view('panel.cashier.invoice.index-pay',
            compact
            ('totalQuitadas', 'totalVencidas', 'total', 'today', 'invoices', 'quitada', 'totalRegister', 'contasVencidas')
        );
    }

    public function createInvoicePay()
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $customers = Customer::where('fornecedor', 'f')->orWhere('fornecedor', 'a')->get();

        return view('panel.cashier.invoice.create-invoice-pay', compact('customers'));
    }

    public function storeInvoicePay(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token', 'valor');

        $valor = $request->only('valor');

        $dataForm['areceber'] = 0;
        $dataForm['quitada'] = 0;

        $dataForm['valor_original'] = str_replace(',', '.', $valor['valor']);

        $dataForm['valor_pendente'] = $dataForm['valor_original'];

        $insert = Invoice::create($dataForm);

        if ($insert) return redirect(route('invoice.pay.index'))->with('success', 'Sucesso ao lançar conta');
    }

    public function paymentInvoicePay($id, PaymentMethod $paymentMethod, Cashier $cashier)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $paymentMethods = $paymentMethod->all();

        $invoice = Invoice::find($id);

        $caixa = $cashier->where('user_id', $user['id'])->first();

        if ($caixa == null) {
            return redirect()->back()->with('error', 'Não há caixa cadastrado');
        } elseif ($caixa['status'] == 'Fechado') {
            return redirect()->back()->with('error', 'O caixa está fechado');
        } else {
            return view('panel.cashier.invoice.payment-invoice-pay', compact('invoice', 'paymentMethods'));
        }
    }

    public function paymentStoreInvoicePay(CashFlow $cashFlow, Request $request, CashHistory $history, Cashier $cashierClass)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $dataForm['valor_pago'] = str_replace(',', 'v', $dataForm['valor_pago']);
        $dataForm['valor_pago'] = str_replace('.', '', $dataForm['valor_pago']);
        $dataForm['valor_pago'] = str_replace('v', '.', $dataForm['valor_pago']);;

        $dataFormSession = $request->session()->put('dataForm', $dataForm);

        $cashier = $cashierClass->where('user_id', $user['id'])->first();

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
        $formaPagamento = PaymentMethod::find($pagTipo[0]);
        $request->session()->put('cashHistory', $cashHistory);
        $cashHistorySession = $request->session()->get('cashHistory');

        $invoiceDB = Invoice::find($dataForm['id']);
        $request->session()->put('invoiceDB', $invoiceDB);
        $invoice['documento'] = $dataForm['documento'];
        if ($formaPagamento['categoria'] == 'dinheiro') {
            if ($dataForm['valor_pago'] >= $dataForm['valor_pendente']) {
                $invoice['quitada'] = 1;
                $invoice['valor_pendente'] = 0;
                $diferenca = $dataForm['valor_pago'] - $dataForm['valor_pendente'];
                $cashHistoryEntrada['cashier_id'] = $cashier['id'];
                $cashHistoryEntrada['abertura_id'] = $lastCashFlow['id'];
                $cashHistoryEntrada['referencia'] = $dataForm['referencia'];
                $cashHistoryEntrada['documento'] = $dataForm['documento'];
                $cashHistoryEntrada['descricao'] = 'Troco';
                $cashHistoryEntrada['valor'] = $diferenca;
                $cashHistoryEntrada['entrada'] = 1;
                $pagTipo = explode(',', $dataForm['pag_tipo']);
                $cashHistoryEntrada['pag_tipo'] = $pagTipo[1];
                $cashHistoryEntrada['pag_tipo_categoria'] = $pagTipo[2];
                $caixa['cash_amount'] = $cashier['cash_amount'] - $dataForm['valor_pendente'];
            } else {
                $caixa['cash_amount'] = $cashier['cash_amount'] - $dataForm['valor_pago'];
                $invoice['quitada'] = 0;
                $invoice['valor_pendente'] = $invoiceDB['valor_pendente'] - $dataForm['valor_pago'];
            }
        } else {
            if($formaPagamento['categoria'] == 'bancaria'){
                $bankAccounts = BankAccount::all();
                $selectAccount = 'invoicePayment';
                return view('panel.banks.select-account', compact('bankAccounts', 'selectAccount', 'invoiceDB'));
            } else {

            }

        }

        DB::beginTransaction();

        $insertHistory = $history->create($cashHistorySession);

        if (isset($cashHistoryEntrada)) {
            $insertHistoryEntrada = $history->create($cashHistoryEntrada);
        }

        $invoiceUpdate = $invoiceDB->update($invoice);

        $cashierUpdate = $cashier->update($caixa);

        if ($invoiceUpdate && $insertHistory && $cashierUpdate) {
            DB::commit();
            $request->session()->forget('invoiceDB');
            $request->session()->forget('cashHistory');
            $request->session()->forget('cashHistoryEntrada');
            $request->session()->forget('cashier');
            $request->session()->forget('dataForm');
            return redirect(route('invoice.pay.index'))->with('success', 'Conta baixada com sucesso');
        } else {
            DB::rollBack();
            return redirect()->back()->with('error', 'Não foi possível continuar. Tente novamente');
            $request->session()->forget('invoiceDB');
            $request->session()->forget('cashHistory');
            $request->session()->forget('cashHistoryEntrada');
            $request->session()->forget('cashier');
            $request->session()->forget('dataForm');
        }

    }

    public function storeInvoicePayBank(Request $request, Cashier $cashier, CashHistory $history, BankAccount $bankAccount)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }


        $dataForm = $request->except('_token');

        $dataFormSession = $request->session()->get('dataForm');
        $invoiceSession = $request->session()->get('invoiceDB');
        $cashHistorySession = $request->session()->get('cashHistory');

        $invoice = Invoice::find($invoiceSession['id']);

        $bankAccountDB = BankAccount::find($dataForm['bank_account']);


        $invoiceUpdate['documento'] = $cashHistorySession['documento'];
        if ($dataFormSession['valor_pago'] >= $dataFormSession['valor_pendente']) {
            $invoiceUpdate['quitada'] = 1;
            $invoiceUpdate['valor_pendente'] = 0;
            $bankAccountUpdate['total_amount'] = $bankAccountDB['total_amount'] - $dataFormSession['valor_pendente'];
        } else {
            $invoiceUpdate['quitada'] = 0;
            $invoiceUpdate['valor_pendente'] = $invoice['valor_pendente'] - $dataFormSession['valor_pago'];
            $bankAccountUpdate['total_amount'] = $bankAccountDB['total_amount'] - $dataFormSession['valor_pago'];
        }

        DB::beginTransaction();

        $updateInvoice = $invoice->update($invoiceUpdate);

        $insertCashHistory = $history->create($cashHistorySession);

        $updateBankAccount = $bankAccountDB->update($bankAccountUpdate);

        if ($insertCashHistory && $updateInvoice && $updateBankAccount) {
            $request->session()->forget('invoiceDB');
            $request->session()->forget('cashHistory');
            $request->session()->forget('dataForm');
            $request->session()->forget('cashier');
            DB::commit();
            return redirect(route('invoice.pay.index'))->with('success', 'Conta baixada com sucesso');
        } else {
            $request->session()->forget('invoiceDB');
            $request->session()->forget('cashHistory');
            $request->session()->forget('dataForm');
            $request->session()->forget('cashier');
            DB::rollBack();
            return redirect()->back()->with('error', 'Não foi possível continuar. Tente novamente');
        }

    }

    public function indexReceive(Invoice $invoice)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $invoices = $invoice->where('areceber', 1)->where('quitada', 0)->paginate($this->totalPaginate);

        $today = date("Y-m-d");

        $contaVencidas = $invoice->where('areceber', 1)->where('quitada', 0)->where('vencimento', '<', $today)->get();

        $contasVencidas = count($contaVencidas);

        $total = $invoices->sum('valor_original');

        $totalVencidas = $invoices->where('vencimento', '<', $today)->where('quitada', 0)->sum('valor_pendente');

        $quitadas = DB::select('SELECT (sum(valor_original))-(sum(valor_pendente)) as  totalQuitadas FROM `invoices` WHERE areceber=1');

        $totalQuitadas = $quitadas[0]->totalQuitadas;

        $totalRegister = count($invoices);

        $quitada = 0;

        return view('panel.cashier.invoice.index-receive',
            compact
            ('total', 'totalQuitadas', 'totalVencidas', 'today', 'contasVencidas', 'invoices', 'totalRegister', 'quitada')
        );
    }

    public function createInvoiceReceive()
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $customers = Customer::where('fornecedor', '<>', 'f')->get();

        return view('panel.cashier.invoice.create-invoice-receive', compact('customers'));
    }

    public function storeInvoiceReceive(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token', 'valor');

        $valor = $request->only('valor');

        $dataForm['areceber'] = 1;
        $dataForm['quitada'] = 0;

        $dataForm['valor_original'] = str_replace(',', '.', $valor['valor']);

        $dataForm['valor_pendente'] = $dataForm['valor_original'];

        $insert = Invoice::create($dataForm);

        if ($insert) return redirect(route('invoice.receive.index'))->with('success', 'Sucesso ao lançar conta');
    }

    public function receivementInvoiceReceive($id, PaymentMethod $paymentMethod)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $paymentMethods = $paymentMethod->all();

        $invoice = Invoice::find($id);

        $bandeiras = Bandeira::all();

        return view('panel.cashier.invoice.receivement-invoice-receive', compact('invoice', 'paymentMethods', 'bandeiras'));
    }

    public function receivementStoreInvoiceReceive(CashFlow $cashFlow, Request $request, CashHistory $history, Cashier $cashier)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $dataForm['valor_pago'] = str_replace(',', 'v', $dataForm['valor_pago']);
        $dataForm['valor_pago'] = str_replace('.', '', $dataForm['valor_pago']);
        $dataForm['valor_pago'] = str_replace('v', '.', $dataForm['valor_pago']);
        $request->session()->put('dataForm', $dataForm);

        $cashierDB = $cashier->where('user_id', $user['id'])->first();

        $lastCashFlow = $cashFlow->where('status', 'Aberto')->where('cashier_id', $cashierDB['id'])->orderBy('created_at', 'desc')->first();

        $cashHistory['cashier_id'] = $cashierDB['id'];
        $cashHistory['abertura_id'] = $lastCashFlow['id'];
        $cashHistory['referencia'] = $dataForm['referencia'];
        $cashHistory['documento'] = $dataForm['documento'];
        $cashHistory['descricao'] = $dataForm['descricao'];
        $cashHistory['valor'] = $dataForm['valor_pago'];
        $cashHistory['entrada'] = 1;
        $fullPagTipo = $dataForm['pag_tipo'];
        $pagTipo = explode(',', $fullPagTipo);
        $formaPagamento = PaymentMethod::find($pagTipo[0]);
        $cashHistory['pag_tipo'] = $pagTipo[1];
        $cashHistory['pag_tipo_categoria'] = $pagTipo[2];
        $request->session()->put('cashHistory', $cashHistory);

        $invoiceDB = Invoice::find($dataForm['id']);

        $invoice['documento'] = $dataForm['documento'];
        if ($dataForm['valor_pago'] < $dataForm['valor_pendente']) {
            $invoice['quitada'] = 0;
            $invoice['valor_pendente'] = $dataForm['valor_pendente'] - $dataForm['valor_pago'];
        } else {
            $invoice['quitada'] = 1;
            $invoice['valor_pendente'] = 0;
        }

        $request->session()->put('invoiceDB', $invoiceDB);
        $invoiceSession = $request->session()->get('invoiceDB');

        if ($formaPagamento['categoria'] == 'bancaria') {
            $bankAccounts = BankAccount::all();
            $selectAccount = 'invoiceReceivement';
            $request->session()->put('cashier', $cashierDB);
            return view('panel.banks.select-account', compact('bankAccounts', 'selectAccount', 'invoiceDB'));
        } elseif($formaPagamento['categoria'] == 'dinheiro') {
            if ($dataForm['valor_pago'] > $dataForm['valor_pendente']) {
                $diferenca = $dataForm['valor_pago'] - $dataForm['valor_pendente'];
                $caixa['cash_amount'] = $cashierDB['cash_amount'] + $dataForm['valor_pago'] - $diferenca;
                $cashHistorySaida['cashier_id'] = $cashierDB['id'];
                $cashHistorySaida['abertura_id'] = $lastCashFlow['id'];
                $cashHistorySaida['referencia'] = $dataForm['referencia'];
                $cashHistorySaida['documento'] = $dataForm['documento'];
                $cashHistorySaida['descricao'] = 'Troco em dinheiro';
                $cashHistorySaida['valor'] = $diferenca;
                $cashHistorySaida['entrada'] = 0;
                $fullPagTipo = $dataForm['pag_tipo'];
                $pagTipo = explode(',', $fullPagTipo);
                $cashHistorySaida['pag_tipo'] = $pagTipo[1];
                $cashHistorySaida['pag_tipo_categoria'] = $pagTipo[2];
            }
            $caixa['cash_amount'] = $cashierDB['cash_amount'] + $dataForm['valor_pago'];
        }

        DB::beginTransaction();

        $cashHistoryInsert = $history->create($cashHistory);

        if (isset($cashHistorySaida)) {
            $cashHistoryInsertSaida = $history->create($cashHistorySaida);
        }

        $invoiceUpdate = $invoiceDB->update($invoice);

        $cashierUpdate = $cashierDB->update($caixa);

        if ($invoiceUpdate && $cashHistoryInsert && $cashierUpdate) {
            DB::commit();
            $request->session()->forget('invoiceDB');
            $request->session()->forget('cashHistory');
            $request->session()->forget('cashier');
            return redirect(route('invoice.receive.index'))->with('success', 'Conta baixada com sucesso');
        } else {
            DB::rollBack();
            return redirect(route('receivement.invoice.receive', ['id' => $invoiceSession['id']]))->with('error', 'Algo deu errado. Tente novamente!');
            $request->session()->forget('invoiceDB');
            $request->session()->forget('cashHistory');
            $request->session()->forget('cashier');
        }

    }

    public function storeInvoiceReceiveBank(Request $request, CashHistory $cashHistory)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $dataFormSession = $request->session()->get('dataForm');
        $invoiceSession = $request->session()->get('invoiceDB');
        $cashHistorySession = $request->session()->get('cashHistory');

        $invoiceDB = Invoice::find($invoiceSession['id']);

        $bankAccount = BankAccount::find($dataForm['bank_account']);

        $invoice['documento'] = $cashHistorySession['documento'];
        if($dataFormSession['valor_pendente'] <= $dataFormSession['valor_pago']){
            $invoice['quitada'] = 1;
            $invoice['valor_pendente'] = 0;
            $updateAccount['total_amount'] = $bankAccount['total_amount'] + $dataFormSession['valor_pendente'];
        } else {
            $invoice['quitada'] = 0;
            $invoice['valor_pendente'] = $invoiceDB['valor_pendente'] - $dataFormSession['valor_pago'];
            $updateAccount['total_amount'] = $bankAccount['total_amount'] + $dataFormSession['valor_pago'];
        }

        DB::beginTransaction();

        $invoiceUpdate = $invoiceDB->update($invoice);

        $cashHistoryInsert = $cashHistory->create($cashHistorySession);

        $bankAccountUpdate = $bankAccount->update($updateAccount);

        if ($invoiceUpdate && $cashHistoryInsert && $bankAccountUpdate) {
            DB::commit();
            return redirect(route('invoice.receive.index'))->with('success', 'Conta baixada com sucesso');
            $request->session()->forget('invoiceDB');
            $request->session()->forget('cashHistory');
            $request->session()->forget('dataForm');
        } else {
            DB::rollBack();
            return redirect()->back()->with('error', 'Algo deu errado. Tente novamente!');
        }

    }

    public function searchPay(Request $request, Invoice $invoice)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $aReceber = 0;

        if (isset($dataForm)) {
            if ($dataForm['quitada'] == 0) {
                $quitada = 0;
            } elseif ($dataForm['quitada'] == 1) {
                $quitada = 1;
            } else {
                $quitada = 2;
            }
        }

        if (!isset($dataForm['totalPaginate'])) {
            $dataForm['totalPaginate'] = 15;
        }

        //dd($dataForm);

        $invoices = $invoice->search($dataForm, $dataForm['totalPaginate'], $aReceber);

        $total = $invoices->sum('valor');

        $today = date('Y-m-d');

        $totalVencidas = $invoices->where('vencimento', '<', $today)->where('quitada', 0)->sum('valor');

        $totalQuitadas = $invoices->where('quitada', 1)->sum('valor');

        $contasVencidas = $invoice->where('vencimento', '<', $today)->where('areceber', 0)->where('quitada', 0)->count();

        $totalRegister = count($invoices);
        return view('panel.cashier.invoice.index-pay',
            compact
            ('totalQuitadas', 'totalVencidas', 'total', 'contasVencidas', 'today', 'invoices', 'quitada', 'dataForm', 'totalRegister')
        );

    }

    public function searchReceive(Request $request, Invoice $invoice)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $aReceber = 1;

        if (isset($dataForm)) {
            if ($dataForm['quitada'] == 0) {
                $quitada = 0;
            } elseif ($dataForm['quitada'] == 1) {
                $quitada = 1;
            } else {
                $quitada = 2;
            }
        }

        if (!isset($dataForm['totalPaginate'])) {
            $dataForm['totalPaginate'] = 15;
        }

        $invoices = $invoice->search($dataForm, $dataForm['totalPaginate'], $aReceber);

        $today = date('Y-m-d');

        $contaVencidas = $invoice->where('vencimento', '<', $today)->where('areceber', 1)->where('quitada', 0)->get();

        $contasVencidas = count($contaVencidas);

        $total = $invoices->sum('valor');

        $totalVencidas = $invoices->where('vencimento', '<', $today)->where('quitada', 0)->sum('valor');

        $totalQuitadas = $invoices->where('quitada', 1)->sum('valor');

        if ($dataForm['lancamento_inicio'] == null
            && $dataForm['lancamento_fim'] == null
            && $dataForm['vencimento_inicio'] == null
            && $dataForm['vencimento_fim'] == null
            && $dataForm['quitada'] == 0) {
            return redirect(route('invoice.receive.index'));
        } else {
            $totalRegister = count($invoices);
            return view('panel.cashier.invoice.index-receive',
                compact
                ('total', 'totalVencidas', 'totalQuitadas', 'contasVencidas', 'today', 'invoices', 'quitada', 'dataForm', 'totalRegister')
            );
        }
    }

    public function aPagarVencidas()
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $today = Date::now()->format('Y-m-d');

        $invoices = Invoice::where('vencimento', '<', $today)->where('areceber', 0)->where('quitada', 0)->paginate(15);

        $totalRegister = count($invoices);

        $total = $invoices->sum('valor_pendente');

        return view('panel.cashier.invoice.apagar-vencidas', compact('invoices', 'totalRegister', 'total'));
    }

    public function aReceberVencidas()
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $today = Date::now()->format('Y-m-d');

        $invoices = Invoice::where('vencimento', '<', $today)->where('areceber', 1)->where('quitada', 0)->paginate(15);

        $totalRegister = count($invoices);

        $total = $invoices->sum('valor_pendente');

        return view('panel.cashier.invoice.areceber-vencidas', compact('invoices', 'totalRegister', 'total'));
    }

    public function consultaFormaPagamento(Request $request)
    {
        $ajax = $request->formapagamento;

        $formaPagamento = PaymentMethod::find($ajax[0]);

        return response()->json(['data' => $formaPagamento]);
    }

}
