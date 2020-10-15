<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Panel\Setting;

class SettingController extends Controller
{
    public function index(Setting $setting)
    {
        $data = $setting->where('setting_id', 1)->first();

        return view('panel.configuracoes.index', compact('data'));
    }

    public function update(Request $request, Setting $setting)
    {
        $dataForm = $request->except('_token');

        $update = $setting->where('setting_id', 1)->update($dataForm);

        if($update){
            return redirect(route('settings.index'))->with('success', 'Configurações atualizadas com sucesso');
        } else {
            return redirect()->back()->with('success', 'Algo deu errado. Tente novamente');
        }
    }
}
