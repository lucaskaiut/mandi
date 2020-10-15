<?php

namespace App\Http\Controllers;

use App\Models\Panel\Athlete;
use Illuminate\Http\Request;
use App\Models\Panel\Invoice;
use Jenssegers\Date\Date;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $today = Date::now()->format('Y-m-d');

        $aReceber = Invoice::where('vencimento', '<', $today)->where('quitada', 0)->where('areceber', 1)->get();

        $totalReceber = $aReceber->sum('valor_pendente');

        $aPagar = Invoice::where('vencimento', '<', $today)->where('quitada', 0)->where('areceber', 0)->get();

        $totalPagar = $aPagar->sum('valor_pendente');

        $atletasInadimplentes = Athlete::where('adimplente', 0)->where('active', 1)->where('deleted', 0)->get();

        return view('panel.home', compact('aReceber','totalReceber', 'aPagar', 'totalPagar', 'atletasInadimplentes'));
    }
}
