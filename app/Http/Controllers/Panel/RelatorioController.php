<?php

namespace App\Http\Controllers\Panel;

use App\Models\Panel\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Jenssegers\Date\Date;
use Illuminate\Support\Facades\Auth;

class RelatorioController extends Controller
{
    public function aReceber()
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }


        return view('panel.relatorios.financeiro.areceber');
    }

    public function aReceberList(Request $request, Invoice $invoice)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $dataForm['quitada'] = 0;

        $titulo = 'Contas a Receber';

        $invoices = $invoice->search($dataForm, '', 1);

        $totalRegister = count($invoices);

        $total = $invoices->sum('valor');

        $date = Date::now()->format('Y-m-d');

        $pdfFileName = 'contasareceber' . $date . '.pdf';

        $pdf = PDF::loadView('panel.pdf.relatorios.financeiro.contas', compact('titulo', 'invoices', 'pdfFileName', 'totalRegister', 'total'));

        return $pdf->stream($pdfFileName);

    }

    public function contasRecebidas()
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        return view('panel.relatorios.financeiro.recebidas');
    }

    public function contasRecebidasList(Request $request, Invoice $invoice)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $dataForm['quitada'] = 1;

        $titulo = 'Contas Recebidas';

        $invoices = $invoice->search($dataForm, '', 1);

        $totalRegister = count($invoices);

        $total = $invoices->sum('valor');

        $date = Date::now()->format('Y-m-d');

        $pdfFileName = 'contasrecebidas' . $date . '.pdf';

        $pdf = PDF::loadView('panel.pdf.relatorios.financeiro.contas', compact('titulo', 'invoices', 'pdfFileName', 'totalRegister', 'total'));

        return $pdf->stream($pdfFileName);

    }

    public function aPagar()
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        return view('panel.relatorios.financeiro.apagar');
    }

    public function aPagarList(Request $request, Invoice $invoice)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $dataForm['quitada'] = 0;

        $titulo = 'Contas a Pagar';

        $invoices = $invoice->search($dataForm, '', 0);

        $totalRegister = count($invoices);

        $total = $invoices->sum('valor');

        $date = Date::now()->format('Y-m-d');

        $pdfFileName = 'contasapagar' . $date . '.pdf';

        $pdf = PDF::loadView('panel.pdf.relatorios.financeiro.contas', compact('titulo', 'invoices', 'pdfFileName', 'totalRegister', 'total'));

        return $pdf->stream($pdfFileName);

    }

    public function contasPagas()
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        return view('panel.relatorios.financeiro.pagas');
    }

    public function contasPagasList(Request $request, Invoice $invoice)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $dataForm['quitada'] = 1;

        $titulo = 'Contas Pagas';

        $invoices = $invoice->search($dataForm, '', 0);

        $totalRegister = count($invoices);

        $total = $invoices->sum('valor');

        $date = Date::now()->format('Y-m-d');

        $pdfFileName = 'contaspagas' . $date . '.pdf';

        $pdf = PDF::loadView('panel.pdf.relatorios.financeiro.contas', compact('titulo', 'invoices', 'pdfFileName', 'totalRegister', 'total'));

        return $pdf->stream($pdfFileName);

    }

    public function pagasRecebidas()
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        return view('panel.relatorios.financeiro.pagas-recebidas');
    }

    public function pagasRecebidasList(Request $request, Invoice $invoice)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $contasRecebidas = $invoice->searchPagasRecebidas($dataForm, 1);

        $valorRecebido = $contasRecebidas->sum('valor');

        $totalRegisterRecebidas = count($contasRecebidas);

        $contasPagas = $invoice->searchPagasRecebidas($dataForm, 0);

        $valorPago = $contasPagas->sum('valor');

        $totalRegisterPagas = count($contasPagas);

        $lucro = $valorRecebido - $valorPago;

        $titulo = 'Contas Pagas x Recebidas';

        $date = Date::now()->format('Y-m-d');

        $pdfFileName = 'contaspagasxrecebidas' . $date . '.pdf';

        $pdf = PDF::loadView('panel.pdf.relatorios.financeiro.pagas-recebidas',
            compact(
                'titulo',
                'contasRecebidas',
                'contasPagas',
                'pdfFileName',
                'totalRegisterPagas',
                'totalRegisterRecebidas',
                'valorPago',
                'valorRecebido',
                'lucro'
            ));

        return $pdf->stream($pdfFileName);

    }

}
