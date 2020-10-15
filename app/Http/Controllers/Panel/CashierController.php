<?php

namespace App\Http\Controllers\Panel;

use App\Http\Requests\Panel\CashierFormRequest;
use App\Models\Panel\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Panel\Cashier;
use App\Models\Panel\CashHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Panel\CashFlow;
use Carbon\Carbon;
use PDF;


class CashierController extends Controller
{

    private $paginate = 15;

    public function index(Cashier $cashier)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $cashiers = $cashier->all();

        return view('panel.cashier.index', compact('cashiers'));
    }

    public function indexUserCashier(Cashier $cashier, CashFlow $cashFlow)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $mainCashier = $cashier->where('user_id', $user['id'])->first();

        if ($mainCashier != null) {

            $lastCashFlow = $cashFlow->where('status', 'Aberto')
                ->where('cashier_id', $mainCashier['id'])
                ->orderBy('created_at', 'desc')
                ->first();

            $current = Carbon::now();

            $caixaMovimentos['inicial'] = $lastCashFlow['saldo_inicial'];

            $caixaMovimentos['entradas'] = DB::table('cash_histories')
                ->where('cashier_id', $mainCashier['id'])
                ->where('entrada', 1)
                ->where('pag_tipo_categoria', '=', 'dinheiro')
                ->whereBetween('created_at', [$lastCashFlow['created_at'], $current])
                ->sum('valor');

            $caixaMovimentos['saidas'] = DB::table('cash_histories')
                ->where('cashier_id', $mainCashier['id'])
                ->where('entrada', 0)
                ->where('pag_tipo_categoria', '=', 'dinheiro')
                ->whereBetween('created_at', [$lastCashFlow['created_at'], $current])
                ->sum('valor');

            $caixaMovimentos['final'] = ($caixaMovimentos['inicial'] + $caixaMovimentos['entradas']) - $caixaMovimentos['saidas'];

            $saldo = $lastCashFlow['saldo_inicial'] + $caixaMovimentos['entradas'] - $caixaMovimentos['saidas'];

            $saldoDisponivel = $cashier->where('status', 'Fechado')->sum('cash_amount');

            return view('panel.cashier.index-user',
                compact('mainCashier', 'caixaMovimentos', 'saldoDisponivel', 'saldo'));

        } else {
            $cashiers = $cashier->count();
            return view('panel.cashier.index-non-cashier', compact('cashiers'));
        }
    }

    public function store(Request $request, Cashier $cashier, CashFlow $cashFlow)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');
        $dataForm['user_id'] = $user['id'];
        $dataForm['user_name'] = $user['name'];

        $userCashiers = $cashier->where('user_id', $user['id'])->count();

        if ($userCashiers > 0) {
            return redirect()->back()->with('error', 'Esse usuário já possui um caixa cadastrado');
        } else {
            DB::beginTransaction();

            $insert = $cashier->create($dataForm);

            $cashFlowData['cashier_id'] = $insert->id;
            $cashFlowData['saldo_inicial'] = 0;

            $insertCashFlow = $cashFlow->create($cashFlowData);

            if ($insert && $insertCashFlow) {
                DB::commit();
                return redirect(route('cashier.index'))->with('success', 'Caixa adicionado com sucesso');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Algo deu errado! Tente novamente');
            }
        }

    }

    public function edit($id)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        $cashiers = Cashier::all();

        $cashier = Cashier::find($id);

        return view('panel.cashier.edit', compact('cashier', 'cashiers'));

    }

    public function update($id, Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $cashier = Cashier::find($id);

        $update = $cashier->update($dataForm);

        if ($update) {
            return redirect(route('cashier.index'))->with('success', 'Caixa atualizado com sucesso');
        } else {
            return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
        }

    }

    public function delete($id)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-delete')) {
            return 'Você não possui acesso à essa área!';
        }
        $cashier = Cashier::find($id);
        if ($cashier['status'] == 'Aberto') {
            return redirect()->back()->with('error', 'O caixa está aberto. Não é possível excluir.');
        } else {
            $delete = Cashier::destroy($id);

            if ($delete) {
                return redirect(route('cashier.index'))->with('success', 'Caixa deletado com sucesso');
            } else {
                return redirect()->back()->with('error', 'Algo deu errado. Tente novamente!');
            }
        }

    }

    public function     close($id, Cashier $cashier, CashFlow $cashFlow, Request $request, CashHistory $history)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        $cashierUpdate['status'] = 'Fechado';

        $cashierDB = $cashier->where('user_id', $user['id'])->first();

        $lastCashFlow = $cashFlow->where('status', 'Aberto')->where('cashier_id', $cashierDB['id'])->orderBy('created_at', 'desc')->first();

        $caixaMovimentos['entradas'] = DB::table('cash_histories')
            ->where('cashier_id', $cashierDB['id'])
            ->where('entrada', 1)
            ->where('pag_tipo_categoria', 'dinheiro')
            ->where('abertura_id', $lastCashFlow['id'])
            ->sum('valor');

        $caixaMovimentos['saidas'] = DB::table('cash_histories')
            ->where('cashier_id', $cashierDB['id'])
            ->where('entrada', 0)
            ->where('pag_tipo_categoria', 'dinheiro')
            ->where('abertura_id', $lastCashFlow['id'])
            ->sum('valor');

        $cashFlowUpdate['status'] = 'Fechado';
        $cashFlowUpdate['saldo_final'] = $lastCashFlow['saldo_inicial'] + $caixaMovimentos['entradas'] - $caixaMovimentos['saidas'];

        $dataForm = $request->except('_token');

        DB::beginTransaction();

        $updateCashier = $cashier->where('id', $id)->update($cashierUpdate);

        $updateCashFlow = $cashFlow->where('id', $lastCashFlow['id'])->update($cashFlowUpdate);

        if ($updateCashier && $updateCashFlow) {
            DB::commit();
            if (isset($dataForm['print_relatorio']) and $dataForm['print_relatorio'] == 1) {

                $cashierHistories = $history->where('cashier_id', $id)
                    ->where('pag_tipo_bancaria', 0)
                    ->where('abertura_id', $lastCashFlow['id'])
                    ->get();
                $caixaMovimentos['inicial'] = $lastCashFlow['saldo_inicial'];

                $caixaMovimentos['final'] = ($caixaMovimentos['inicial'] + $caixaMovimentos['entradas']) - $caixaMovimentos['saidas'];
                $pdfFileName = 'fechamento.pdf';
                $pdf = PDF::loadView('panel.pdf.caixa-fechamento', compact('cashierHistories', 'caixaMovimentos'));
                return $pdf->stream($pdfFileName);
            } else {
                return redirect(route('caixa'))->with('success', 'Caixa fechado com sucesso');
            }
        } else {
            DB::rollBack();
            return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
        }

    }

    public function open($id, CashFlow $cashFlow, CashierFormRequest $request)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        $cashier = Cashier::find($id);

        $cashierUpdate['status'] = 'Aberto';

        $cashFlowInsert['cashier_id'] = $id;

        $dataForm = $request->except('_token');
        if(!isset($dataForm['valor'])) {
            $dataForm['valor'] = 0;
        }

        $saldo = Cashier::where('status', 'Fechado')->sum('cash_amount');

        if($dataForm['valor'] > $saldo){
            return redirect()->back()->with('error', 'Valor digitado é maior do que o saldo disponível');
        } else {

            $cashFlowInsert['saldo_inicial'] = $dataForm['valor'];

            DB::beginTransaction();

            $insertCashFlow = $cashFlow->create($cashFlowInsert);

            $updateCashier = $cashier->update($cashierUpdate);

            if ($insertCashFlow && $updateCashier) {
                DB::commit();
                return redirect(route('caixa'))->with('Abertura realizada com sucesso');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
            }
        }
    }

    public function cashierHistory(CashHistory $history, Cashier $cashier, CashFlow $cashFlow)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $cashierDB = $cashier->where('user_id', $user['id'])->first();

        if ($cashierDB['status'] == 'Fechado') {
            return redirect(route('caixa'))->with('success', 'O caixa está fechado');
        } else {
            $lastCashFlow = $cashFlow->where('status', 'Aberto')->where('cashier_id', $cashierDB['id'])->orderBy('created_at', 'desc')->first();
            $current = Carbon::now();
            $cashierHistories = $history->where('cashier_id', $cashierDB['id'])
                ->where('pag_tipo_bancaria', 0)
                ->where('abertura_id', $lastCashFlow['id'])
                ->paginate($this->paginate);

            return view('panel.cashier.histories', compact('cashierHistories'));
        }

    }

    public function cashierCashHistory(CashHistory $cashHistory, Cashier $cashier)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $cashierDB = $cashier->where('user_id', $user['id'])->first();

        $cashierHistories = $cashHistory->where('pag_tipo_bancaria', 0)->where('cashier_id', $cashierDB['id'])->paginate($this->paginate);

        return view('panel.cashier.histories', compact('cashierHistories'));

    }

    public function cashierBankHistory(CashHistory $cashHistory, Cashier $cashier)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $cashierDB = $cashier->where('user_id', $user['id'])->first();

        $cashierHistories = $cashHistory->where('pag_tipo_bancaria', 1)->where('cashier_id', $cashierDB['id'])->paginate($this->paginate);

        return view('panel.cashier.histories', compact('cashierHistories'));

    }

    public function accountCashierTransfer(Cashier $cashierClass)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        $bankAccounts = BankAccount::all();

        $cashier = $cashierClass->where('user_id', $user['id'])->first();
        if ($cashier['status'] == 'Fechado') {
            return redirect(route('caixa'))->with('success', 'O caixa está fechado');
        } else {
            if (count($bankAccounts) == 0) {
                return redirect()->back()->with('error', 'Não há nenhuma conta cadastrada');
            } else {
                $op = 'accountToCashier';
                return view('panel.banks.select-account-transfer', compact('bankAccounts', 'op', 'cashier'));
            }
        }

    }

    public function accountCashierTransferStore(Request $request, Cashier $cashier, CashHistory $history)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $bankAccount = BankAccount::find($dataForm['bank_account']);

        $mainCashier = $cashier->where('user_id', $user['id'])->first();

        if ($mainCashier['status'] == 'Fechado') {
            return redirect(route('caixa'))->with('error', 'O caixa está fechado');
        } else {
            if ($dataForm['valor'] > $bankAccount['total_amount']) {
                return redirect()->back()->with('error', 'O saldo disponível na conta selecionada é menor do que o valor digitado. O saldo na conta é de R$' . $bankAccount['total_amount']);
            }
            $cashHistory['cashier_id'] = $mainCashier['id'];
            $cashHistory['referencia'] = 'T';
            $cashHistory['documento'] = $dataForm['documento'];
            $cashHistory['descricao'] = $dataForm['descricao'];
            $cashHistory['valor'] = $dataForm['valor'];
            $cashHistory['entrada'] = 1;
            $cashHistory['pag_tipo'] = 'Dinheiro';
            $cashHistory['pag_tipo_bancaria'] = 0;

            $bankHistory['cashier_id'] = $mainCashier['id'];
            $bankHistory['referencia'] = 'T';
            $bankHistory['documento'] = $dataForm['documento'];
            $bankHistory['descricao'] = $dataForm['descricao'];
            $bankHistory['valor'] = $dataForm['valor'];
            $bankHistory['entrada'] = 0;
            $bankHistory['pag_tipo'] = 'Saque';
            $bankHistory['pag_tipo_bancaria'] = 1;

            $updateCashier['cash_amount'] = $mainCashier['cash_amount'] + $dataForm['valor'];

            $updateBankAccount['total_amount'] = $bankAccount['total_amount'] - $dataForm['valor'];

            DB::beginTransaction();

            $cashierUpdate = $cashier->where('user_id', $user['id'])->update($updateCashier);

            $bankUpdate = $bankAccount->update($updateBankAccount);

            $cashHistoryInsert = $history->create($cashHistory);

            $bankHistoryInsert = $history->create($bankHistory);

            if ($cashierUpdate && $bankUpdate && $cashHistoryInsert && $bankHistoryInsert) {
                DB::commit();
                return redirect(route('caixa'))->with('success', 'Transferência realizada com sucesso');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
            }
        }

    }

    public function cashierAccountTransfer(Cashier $cashierClass)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        $bankAccounts = BankAccount::all();

        $cashier = $cashierClass->where('user_id', $user['id'])->first();

        if ($cashier['status'] == 'Fechado') {
            return redirect(route('caixa'))->with('error', 'O caixa está fechado');
        } else {
            if (count($bankAccounts) == 0) {
                return redirect()->back()->with('error', 'Não há conta cadastrada');
            } else {
                $op = 'cashierToAccount';
                return view('panel.banks.select-account-transfer', compact('bankAccounts', 'cashier', 'op'));
            }
        }

    }

    public function cashierAccountTransferStore(Request $request, Cashier $cashier, CashHistory $history)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $bankAccount = BankAccount::find($dataForm['bank_account']);

        $mainCashier = $cashier->where('user_id', $user['id'])->first();

        if ($mainCashier['stauts'] == 'Fechado') {
            return redirect()->with('error', 'O caixa está fechado');
        } else {

            if ($dataForm['valor'] > $mainCashier['cash_amount']) {
                return redirect()->back()->with('error', 'O saldo disponível em caixa é menor do que o valor digitado. O saldo em caixa é de R$' . $mainCashier['cash_amount']);
            } else {
                $cashHistory['cashier_id'] = $mainCashier['id'];
                $cashHistory['referencia'] = 'T';
                $cashHistory['documento'] = $dataForm['documento'];
                $cashHistory['descricao'] = $dataForm['descricao'];
                $cashHistory['valor'] = $dataForm['valor'];
                $cashHistory['entrada'] = 0;
                $cashHistory['pag_tipo'] = 'Dinheiro';
                $cashHistory['pag_tipo_bancaria'] = 0;

                $bankHistory['cashier_id'] = $mainCashier['id'];
                $bankHistory['referencia'] = 'T';
                $bankHistory['documento'] = $dataForm['documento'];
                $bankHistory['descricao'] = $dataForm['descricao'];
                $bankHistory['valor'] = $dataForm['valor'];
                $bankHistory['entrada'] = 1;
                $bankHistory['pag_tipo'] = 'Saque';
                $bankHistory['pag_tipo_bancaria'] = 1;

                $updateCashier['cash_amount'] = $mainCashier['cash_amount'] - $dataForm['valor'];

                $updateBankAccount['total_amount'] = $bankAccount['total_amount'] + $dataForm['valor'];

                DB::beginTransaction();

                $cashierUpdate = $cashier->where('user_id', $user['id'])->update($updateCashier);

                $bankUpdate = $bankAccount->update($updateBankAccount);

                $cashHistoryInsert = $history->create($cashHistory);

                $bankHistoryInsert = $history->create($bankHistory);

                if ($cashierUpdate && $bankUpdate && $cashHistoryInsert && $bankHistoryInsert) {
                    DB::commit();
                    return redirect(route('caixa'))->with('success', 'Transferência realizada com sucesso');
                } else {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
                }
            }
        }
    }

    public function cashierTransfer(Cashier $cashierClass)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        $cashiers = DB::table('cashiers')->select()->where('user_id', '<>', $user['id'])->get();

        $cashier = $cashierClass->where('user_id', $user['id'])->first();

        if ($cashier['status'] == 'Fechado') {
            return redirect(route('caixa'))->with('error', 'O caixa está fechado');
        } else {
            if (count($cashiers) == 0) {
                return redirect()->back()->with('error', 'Não há outro caixa para efetuar transação');
            } else {
                return view('panel.cashier.select-cashier', compact('cashiers', 'cashier'));
            }
        }
    }

    public function cashierTransferStore($id, Cashier $cashier, Request $request, CashHistory $history)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $cashierOut = $cashier->where('user_id', $user['id'])->first();

        if ($cashierOut['status'] == 'Fechado') {
            return redirect(route('caixa'))->with('error', 'O caixa está fechado');
        } else {

            $cashierIn = Cashier::find($dataForm['cashier_id']);

            if ($dataForm['valor'] > $cashierOut['cash_amount']) {
                return redirect()->back()->with('error', 'O valor digitado é maior do que o saldo em caixa. O saldo disponível é R$' . $cashierOut['cash_amount']);
            } else {
                $cashierOutUpdate['cash_amount'] = $cashierOut['cash_amount'] - $dataForm['valor'];

                $cashierInUpdate['cash_amount'] = $cashierIn['cash_amount'] + $dataForm['valor'];

                $cashHistoryIn['cashier_id'] = $cashierIn['id'];
                $cashHistoryIn['referencia'] = 'T';
                $cashHistoryIn['documento'] = '#';
                $cashHistoryIn['descricao'] = $dataForm['descricao'];
                $cashHistoryIn['valor'] = $dataForm['valor'];
                $cashHistoryIn['entrada'] = 1;
                $cashHistoryIn['pag_tipo'] = 'Dinheiro';
                $cashHistoryIn['pag_tipo_bancaria'] = 0;

                $cashHistoryOut['cashier_id'] = $cashierOut['id'];
                $cashHistoryOut['referencia'] = 'T';
                $cashHistoryOut['documento'] = '#';
                $cashHistoryOut['descricao'] = $dataForm['descricao'];
                $cashHistoryOut['valor'] = $dataForm['valor'];
                $cashHistoryOut['entrada'] = 0;
                $cashHistoryOut['pag_tipo'] = 'Dinheiro';
                $cashHistoryOut['pag_tipo_bancaria'] = 0;

                DB::commit();

                $updateCashierOut = $cashier->where('id', $cashierOut['id'])->update($cashierOutUpdate);

                $updateCashierIn = $cashier->where('id', $cashierIn['id'])->update($cashierInUpdate);

                $cashHistoryInInsert = $history->create($cashHistoryIn);

                $cashHistoryOutInsert = $history->create($cashHistoryOut);

                if ($updateCashierOut && $updateCashierIn && $cashHistoryInInsert && $cashHistoryOutInsert) {
                    DB::commit();
                    return redirect(route('caixa'))->with('success', 'Transferência realizada com sucesso');
                } else {
                    return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
                }
            }
        }
    }

}
