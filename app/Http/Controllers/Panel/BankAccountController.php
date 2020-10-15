<?php

namespace App\Http\Controllers\Panel;

use App\Models\Panel\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class BankAccountController extends Controller
{

    private $totalPaginate = 15;

    public function index(BankAccount $bankAccount)
    {
        $user = Auth::user();

        if(!$user->hasPermissionTo('fin-list'))
        {
            return 'Você não possui acesso à essa área!';
        }

        $bankAccounts = $bankAccount->paginate($this->totalPaginate);

        return view('panel.banks.index', compact('bankAccounts'));
    }

    public function create()
    {
        $user = Auth::user();

        if(!$user->hasPermissionTo('fin-create'))
        {
            return 'Você não possui acesso à essa área!';
        }

        return view('panel.banks.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if(!$user->hasPermissionTo('fin-create'))
        {
            return 'Você não possui acesso à essa área!';
        }

        $bankAccount = $request->except('_token');

        $insert = BankAccount::create($bankAccount);

        if ($insert) return redirect(route('bank.account.index'))->with('success', 'Conta cadastrada com sucesso');
    }

    public function edit($id)
    {
        $user = Auth::user();

        if(!$user->hasPermissionTo('fin-edit'))
        {
            return 'Você não possui acesso à essa área!';
        }

        $bankAccount = BankAccount::find($id);

        return view('panel.banks.edit', compact('bankAccount'));
    }

    public function update($id, BankAccount $bankAccount, Request $request)
    {

        $user = Auth::user();

        if(!$user->hasPermissionTo('fin-edit'))
        {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $update = $bankAccount->where('id', $id)->update($dataForm);

        if ($update) return redirect(route('bank.account.index'))->with('success', 'Conta atualizada com sucesso');

    }

    public function delete($id)
    {
        $user = Auth::user();

        if(!$user->hasPermissionTo('fin-delete'))
        {
            return 'Você não possui acesso à essa área!';
        }

        $bankAccount = BankAccount::find($id);

        $delete = $bankAccount->delete();

        if ($delete) return redirect(route('bank.account.index'))->with('success', 'Conta deletada com sucesso');
    }

}
