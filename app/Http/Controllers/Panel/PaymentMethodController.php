<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Panel\PaymentMethod;
use Illuminate\Support\Facades\Auth;


class PaymentMethodController extends Controller
{
    public function index(PaymentMethod $paymentMethod)
    {
        $user = Auth::user();

        if(!$user->hasPermissionTo('fin-list'))
        {
            return 'Você não possui acesso à essa área!';
        }

        $paymentMethods = $paymentMethod->all();

        return view('panel.cashier.paymentMethods.index', compact('paymentMethods'));
    }

    public function store(Request $request)
    {

        $user = Auth::user();

        if(!$user->hasPermissionTo('fin-create'))
        {
            return 'Você não possui acesso à essa área!';
        }

        $paymentMethod = $request->except('_token');

        $insert = PaymentMethod::create($paymentMethod);

        if($insert) return redirect(route('payment.method.index'))->with('success', 'Forma de pagamento adicionado com sucesso');
    }

    public function edit($id, PaymentMethod $paymentMethod)
    {
        $user = Auth::user();

        if(!$user->hasPermissionTo('fin-edit'))
        {
            return 'Você não possui acesso à essa área!';
        }

        $paymentMethods = $paymentMethod->all();

        $paymentMethodToEdit = PaymentMethod::find($id);

        return view('panel.cashier.paymentMethods.edit', compact('paymentMethods', 'paymentMethodToEdit'));
    }

    public function update($id, PaymentMethod $paymentMethod, Request $request)
    {
        $user = Auth::user();

        if(!$user->hasPermissionTo('fin-edit'))
        {
            return 'Você não possui acesso à essa área!';
        }

        $paymentMethodToEdit = $request->except('_token');

        $update = $paymentMethod->where('id', $id)->update($paymentMethodToEdit);

        if($update) return redirect(route('payment.method.index'))->with('success', 'Forma de pagamento alterada com sucesso');

    }

    public function delete($id)
    {
        $user = Auth::user();

        if(!$user->hasPermissionTo('fin-delete'))
        {
            return 'Você não possui acesso à essa área!';
        }

        $payment = PaymentMethod::destroy($id);

        if($payment) return redirect(route('payment.method.index'))->with('success', 'Forma de pagamento apagada com sucesso');
    }

}
