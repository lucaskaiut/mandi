<?php

namespace App\Http\Controllers\Panel;

use App\Http\Requests\Panel\CompanyFormRequest;
use App\Models\Panel\Category;
use Illuminate\Http\Request;
use App\Models\Panel\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index(Company $company)
    {

        $companies = $company->all();

        return view('panel.company.index', compact('companies'));
    }

    public function create()
    {

        return view('panel.company.create');
    }

    public function store(CompanyFormRequest $request, Company $company)
    {

        $dataForm = $request->except('_token');

        if(isset($dataForm['cpf']) && isset($dataForm['cnpj'])){
            return redirect()->back()->with('error', 'Escolha entre CPF e CNPJ');
        }

        $insert = $company->create($dataForm);

        if($insert){
            return redirect(route('company.index'))->with('success', 'Empresa cadastrada com sucesso');
        } else {
            return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
        }
    }

    public function edit($id)
    {

        $company = Company::find($id);

        return view('panel.company.edit', compact('company'));
    }

    public function update($id, CompanyFormRequest $request)
    {

        $company = Company::find($id);

        $dataForm = $request->except('_token');

        $update = $company->update($dataForm);

        if($update){
            return redirect(route('company.index'))->with('success', 'Empresa alterada com sucesso');
        } else {
            return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
        }
    }

    public function delete($id)
    {

        $companies = Company::all();

        if(count($companies) > 1){
            $delete = Company::destroy($id);
            if($delete){
                return redirect(route('company.index'))->with('success', 'Empresa deletada com sucesso');
            } else {
                return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
            }
        } else {
            return redirect()->back()->with('error', 'Só há uma empresa cadastrada. Não é possível apagar');
        }

    }

    public function categorias($id)
    {

        $empresa = Company::find($id);

        $relacionadas = Company::find($empresa->id)->categories()->get();

        $categories = Category::whereDoesntHave('companies', function ($query) use ($id) {
            $query->where('companies.id', $id);
        })->get();

        return view('panel.company.categoria-empresa', compact('empresa', 'categories', 'relacionadas'));
    }

    public function addCategoria($idEmpresa, $idCategoria)
    {

        $relation['company_id'] = $idCategoria;
        $relation['category_id'] = $idEmpresa;

        $insert = DB::table('category_company')->insert(['company_id' => $idEmpresa, 'category_id' => $idCategoria]);

        if($insert){
            return redirect(route('company.categorias', ['id' => $idEmpresa]))->with('success', 'Categoria adicionada com sucesso');
        } else {
            return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
        }
    }

    public function removeCategoria($idEmpresa, $idCategoria)
    {

        $delete = DB::table('category_company')
            ->where('company_id', $idEmpresa)
            ->where('category_id', $idCategoria)
            ->delete();

        if($delete){
            return redirect(route('company.categorias', ['id' => $idEmpresa]))->with('success', 'Categoria removida com sucesso');
        } else {
            return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
        }
    }

    public function athleteCreate(Request $request)
    {


        $companyID = $request->company;

        $data = Company::find($companyID)->categories()->get();

        return response()->json(['data'=>$data]);
    }

}
