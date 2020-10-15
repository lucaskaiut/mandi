<?php

namespace App\Http\Controllers\Panel;

use App\Models\Panel\Company;
use Illuminate\Support\Facades\Auth;
use App\Models\Panel\Athlete;
use App\Models\Panel\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    private $totalPaginate = 15;

    public function index(Category $category)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $categories = $category->paginate($this->totalPaginate);

        $empresas = Company::all();

        return view('panel.athlete.category.index', compact('categories', 'empresas'));

    }

    public function store(Request $request, Category $category)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $insert = $category->create($dataForm);

        if($insert)
        {
            return redirect(route('category.index'))->with('success', 'Categoria adicionada com sucesso');
        } else
        {
            return redirect()->back()->with('error', 'Não foi possível adicionar a categoria. Tente novamente');
        }

    }

    public function edit($id)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        $categories = Category::all();

        $category = Category::find($id);

        $empresas = Company::all();

        return view('panel.athlete.category.edit', compact('category', 'categories', 'empresas'));
    }

    public function update($id, Request $request)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        $category = Category::find($id);

        $dataForm = $request->except('_token');

        $update = $category->update($dataForm);

        if($update)
        {
            return redirect(route('category.index'))->with('success', 'Categoria atualizada com sucesso');
        } else
        {
            return redirect()->back()->with('error', 'Não foi possível atualizar a categoria. Tente novamente mais tarde');
        }

    }

    public function delete($id)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-delete')) {
            return 'Você não possui acesso à essa área!';
        }

        $delete = Category::destroy($id);

        if($delete)
        {
            return redirect(route('category.index'))->with('success', 'Categoria apagada com sucesso');
        } else
        {
            return redirect()->back()->with('error', 'Não foi possível apagar a categoria. Tente novamente mais tarde');
        }

    }

}
