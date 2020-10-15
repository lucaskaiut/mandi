<?php

namespace App\Http\Controllers\Panel;

use App\Http\Requests\Panel\CustomerFormRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Panel\Customer;

class SupplierController extends Controller
{
    public function index()
    {

        $customers = Customer::all();

        return view('panel.customer.index', compact('customers'));
    }

    public function create()
    {
        return view('panel.customer.create');
    }

    public function store(CustomerFormRequest $request, Customer $customer)
    {
        $dataForm = $request->except('_token');

        if(!isset($dataForm['ativo'])){
            $dataForm['ativo'] = 0;
        }

        if(isset($dataForm['cpf']) && isset($dataForm['cnpj'])){
            return redirect()->back()->with('error', 'Escolha entre CPF e CNPJ');
        }

        if(!isset($dataForm['juridica'])){
            $dataForm['juridica'] = 0;
        }
        if($dataForm['juridica'] == 0 && $dataForm['fantasia'] == null){
            $dataForm['fantasia'] = $dataForm['razao_social'];
        }

        $insert = $customer->create($dataForm);

        if($insert){
            return redirect(route('customer.index'))->with('success', 'Registro adicionado com sucesso');
        }

    }

    public function edit($id)
    {
        $customer = Customer::find($id);

        return view('panel.customer.edit', compact('customer'));
    }

    public function update($id, CustomerFormRequest $request)
    {
        $customer = Customer::find($id);

        $dataForm = $request->except('_token');

        if(isset($dataForm['cpf']) && isset($dataForm['cnpj'])){
            return redirect()->back()->with('error', 'Escolha entre CPF e CNPJ');
        }

        if(!isset($dataForm['fornecedor'])){
            $dataForm['fornecedor'] = 'a';
        }

        if(!isset($dataForm['ativo'])){
            $dataForm['ativo'] = 0;
        }

        if(!isset($dataForm['juridica'])){
            $dataForm['juridica'] = 0;
        }

        if($dataForm['juridica'] == 0 && $dataForm['fantasia'] == null){
            $dataForm['fantasia'] = $dataForm['razao_social'];
        }

        $update = $customer->update($dataForm);

        if($update){
            return redirect(route('customer.index'))->with('success', 'Registro alterado com sucesso');
        }

    }

    public function delete($id)
    {
        $delete = Customer::destroy($id);

        if($delete){
            return redirect(route('customer.index'))->with('success', 'Registro apagado com sucesso');
        } else {
            return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
        }

    }

    public function search(Request $request, Customer $customer)
    {

        $dataForm = $request->except('_token');

        $customers = $customer->search($dataForm);

        $title = 'Fornecedores';

        return view('panel.customer.index', compact('customers', 'title', 'dataForm'));

    }

}
