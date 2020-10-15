<?php

namespace App\Http\Controllers\Panel;

use App\Models\Panel\Bandeira;
use App\Models\Panel\BankAccount;
use App\Models\Panel\CartaoMovimento;
use App\Models\Panel\Invoice;
use App\Models\Panel\Monetary;
use App\Models\Panel\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Panel\Mensalidade;
use App\Models\Panel\Athlete;
use App\Models\Panel\Cashier;
use App\Models\Panel\CashHistory;
use Illuminate\Support\Facades\DB;
use App\Models\Panel\PaymentMethod;
use App\Models\Panel\CashFlow;
use App\Mail\AthleteMensalidade;
use Illuminate\Support\Facades\Mail;
use PDF;
use Carbon\Carbon;
use Jenssegers\Date\Date;

class MensalidadeController extends Controller
{

    private $mensalidadePaginate = 15;

    public function __construct()
    {
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    }

    public function index(Mensalidade $mensalidade)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $mensalidades = $mensalidade->paginate($this->mensalidadePaginate);

        $total = $mensalidade->sum('amount');

        $totalRegister = count($mensalidades);

        return view('panel.athlete.mensalidade.index', compact('mensalidades', 'total', 'totalRegister'));
    }

    public function athleteIndex($id, Mensalidade $mensalidade)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $athlete = Athlete::find($id);

        $mensalidades = $mensalidade->where('athlete_id', $id)->paginate($this->mensalidadePaginate);
        foreach($mensalidades as $mensalidade) {
            $refMes = explode(',', $mensalidade['ref_mes']);
            $mensalidade['ref_mes'] = $refMes[0];
        }

        return view('panel.athlete.mensalidade.athlete-index', compact('mensalidades', 'athlete'));
    }

    public function createMensalidade($id, PaymentMethod $paymentMethod, Cashier $cashier)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $paymentMethods = $paymentMethod->all();

        $cashiers = $cashier->all();

        if(count($cashiers) == 0){
            return redirect()->back()->with('error', 'Não há nenhum caixa cadastrado');
        }

        $athlete = Athlete::find($id);

        return view('panel.athlete.mensalidade.create', compact('athlete', 'paymentMethods'));

    }

    public function storeMensalidade(Invoice $invoice, Setting $setting, Monetary $monetary, $id, CashFlow $cashFlow, Request $request, Mensalidade $mensalidades, Cashier $cashier, PaymentMethod $paymentMethod, CashHistory $history)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $athlete = Athlete::find($id);

        $dataForm = $request->except('_token');
        $dataForm['valor_pago'] = $dataForm['amount'];

        //mensalidade
        $mensalidadeDB = $mensalidades->orderBy('created_at', 'desc')->first();
        $mensalidade['athlete_id'] = $athlete['id'];
        if ($mensalidadeDB != null) {
            $mensalidade['recibo'] = $mensalidadeDB['recibo'] + 1;
        } else {
            $mensalidade['recibo'] = 1;
        }
        $mensalidade['amount'] = str_replace(',', '.', $dataForm['amount']);
        $mensalidade['atleta'] = $athlete['name'];
        $mensalidade['ref_mes'] = $dataForm['ref_mes'];
        $mensalidade['pagamento'] = $dataForm['pagamento'];
        $mensalidade['rg'] = $athlete['rg'];
        $fullPagTipo = $dataForm['pag_tipo'];
        $pagTipo = explode(',', $fullPagTipo);
        $mensalidade['pag_tipo'] = $pagTipo['1'];
        $request->session()->put('mensalidade', $mensalidade);
        $mensalidadeSession = $request->session()->get('mensalidade');
        $request->session()->put('atleta', $athlete);

        //caixa
        $caixaDB = $cashier->where('user_id', $user['id'])->first();
        $caixa['total_amount'] = $caixaDB['total_amount'] + $mensalidade['amount'];

        $lastCashFlow = $cashFlow->where('status', 'Aberto')->where('cashier_id', $caixaDB['id'])->orderBy('created_at', 'desc')->first();

        $formaPagamento = PaymentMethod::find($pagTipo[0]);

        //historico de caixa
        $cashHistory['cashier_id'] = $caixaDB['id'];
        $cashHistory['abertura_id'] = $lastCashFlow['id'];
        $cashHistory['referencia'] = 'M';
        $cashHistory['documento'] = $mensalidade['recibo'];
        $cashHistory['descricao'] = 'Mensalidade - ' . $athlete['name'] . ' - ' . $mensalidade['ref_mes'];
        $cashHistory['valor'] = $mensalidade['amount'];
        $cashHistory['entrada'] = 1;
        $cashHistory['pag_tipo'] = $mensalidade['pag_tipo'];
        $cashHistory['pag_tipo_categoria'] = $formaPagamento['categoria'];
        $request->session()->put('cashHistory', $cashHistory);
        $cashHistorySession = $request->session()->get('cashHistory');

        $refMesArray = explode(',', $dataForm['ref_mes']);
        $refMes = $refMesArray[1];

        if(strlen($refMes) == 1){
            $refMes = '\'0'.$refMes.'\'';
        } else {
            $refMes = '\''.$refMes.'\'';
        }

        $atleta['adimplente'] = 1;
        $atleta['ultimo_recibo'] = $refMes;

        $request->session()->put('atletaUpdate', $atleta);

        if (!isset($dataForm['mailSubmit'])) {
            $dataForm['mailSubmit'] = 0;
        }

        $mailSubmit = $dataForm['mailSubmit'];

        $request->session()->put('mailSubmit', $mailSubmit);

        if ($formaPagamento->categoria == 'bancaria') {
            $bankAccounts = BankAccount::all();
            if(count($bankAccounts) == 0){
                return redirect()->back()->with('error', 'Para baixar uma conta com formas de recebimento bancárias deve haver ao menos uma conta corrente cadastrada');
            }
            $selectAccount = 'mensalidade';
            return view('panel.banks.select-account', compact('mensalidadeSession', 'bankAccounts', 'selectAccount'));
        } elseif ($formaPagamento->categoria == 'dinheiro') {
            $caixa['cash_amount'] = $caixaDB['cash_amount'] + $mensalidade['amount'];
            $dateWeekDay = ucfirst(Date::now()->format('l'));
            $dateDay = Date::now()->format('j');
            $dateMonth = ucfirst(Date::now()->format('F'));
            $dateYear = Date::now()->format('Y');

            $date = $dateWeekDay . ', ' . $dateDay . ' de ' . $dateMonth . ' de ' . $dateYear;

            $valor = $mensalidade['amount'];

            $valueExt = $monetary->extenso($valor, true);

            $urlToSave = public_path('/pdf/');

            $pdfFileName = $mensalidade['recibo'] . '-' . str_slug($athlete['name'], '-') . '.pdf';
            $pdf = PDF::loadView('panel.pdf.recibo-mensalidade', compact('mensalidade', 'pdfFileName', 'valueExt', 'date'));
            $pdf->save($urlToSave . $pdfFileName);

            $urlPdf = $urlToSave . $pdfFileName;

            if ($dataForm['mailSubmit'] == 1) {
                Mail::to($athlete->email)->send(new AthleteMensalidade($urlPdf, $mensalidade['ref_mes']));
            }
        } else {
            $bandeiras = Bandeira::all();
            if(count($bandeiras) == 0){
                return redirect()->back()->with('error', 'Para lançar uma conta no cartão, cadastre pelo menos uma bandeira');
            }
            return view('panel.cartao.cartaomovimentos.mensalidade', compact('mensalidadeSession', 'bandeiras', 'dataForm', 'athlete'));
        }
        $request->session()->put('cashier', $caixa);
        $cashierSession = $request->session()->get('cashier');

        $settings = $setting->where('setting_id', 1)->first();

        DB::beginTransaction();

        $insertMensalidade = $mensalidades->create($mensalidadeSession);

        $insertCashHistory = $history->create($cashHistorySession);

        if($caixaDB['status'] <> 'Fechado'){
            $updateCashier = $caixaDB->update($cashierSession);
        } elseif($settings['baixa_mensalidade_caixa_fechado'] == 1) {
            $contaReceber['descricao'] = 'Mensalidade - ' . $athlete['name'] . ' - ' . $mensalidade['ref_mes'];
            $contaReceber['documento'] = $mensalidade['recibo'];
            $contaReceber['valor_original'] = $mensalidade['amount'];
            $contaReceber['valor_pendente'] = $mensalidade['amount'];
            $contaReceber['areceber'] = 1;
            $contaReceber['vencimento'] = date_format(now(), 'Y-m-d');
            $contaReceber['quitada'] = 0;
            $contaReceber['fornecedor_id'] = $athlete['id'];
            $contaReceber['razao_social'] = $athlete['name'];
            $updateCashier = $invoice->create($contaReceber);
        } else {
            return redirect()->back()->with('error', 'Não é possível baixar uma mensalidade em dinheiro quando o caixa estiver fechado. Abra o caixa ou altere a configuração em "Utilitários > Configurações > Administração do Sistema"');
        }

        $updateAtleta = $athlete->update($atleta);

        if ($insertCashHistory && $insertMensalidade && $updateCashier && $updateAtleta) {
            $request->session()->forget('mensalidade');
            $request->session()->forget('cashHistory');
            $request->session()->forget('cashier');
            DB::commit();
            return redirect(route('athlete.mensalidades', ['id' => $mensalidade['athlete_id']]))->with('success', 'Sucesso ao adicionar mensalidade!');
        } else {
            $request->session()->forget('mensalidade');
            $request->session()->forget('cashHistory');
            $request->session()->forget('cashier');
            DB::rollBack();
            return redirect()->back()->with('error', 'Algo deu errado, tente novamente');
        }

    }

    public function storeMensalidadeBank(Request $request, Mensalidade $mensalidades, CashHistory $history, Monetary $monetary)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-create')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $mensalidadeSession = $request->session()->get('mensalidade');
        $cashHistorySession = $request->session()->get('cashHistory');
        $atletaSession = $request->session()->get('atleta');
        $atleta = $request->session()->get('atletaUpdate');
        $athlete = Athlete::find($mensalidadeSession['athlete_id']);

        $bankAccountDB = BankAccount::find($dataForm['bank_account']);

        $bankAccountUpdate['total_amount'] = $bankAccountDB['total_amount'] + $mensalidadeSession['amount'];

        DB::beginTransaction();

        $insertMensalidade = $mensalidades->create($mensalidadeSession);

        $insertCashHistory = $history->create($cashHistorySession);

        $updateBankAccount = $bankAccountDB->update($bankAccountUpdate);

        $updateAtleta = $athlete->update($atleta);

        if ($insertMensalidade && $insertCashHistory && $updateBankAccount && $updateAtleta) {
            DB::commit();
            $request->session()->forget('mensalidade');
            $request->session()->forget('cashHistory');
            $dateWeekDay = ucfirst(Date::now()->format('l'));
            $dateDay = Date::now()->format('j');
            $dateMonth = ucfirst(Date::now()->format('F'));
            $dateYear = Date::now()->format('Y');

            $date = $dateWeekDay . ', ' . $dateDay . ' de ' . $dateMonth . ' de ' . $dateYear;

            $valor = $mensalidadeSession['amount'];

            $valueExt = $monetary->extenso($valor, true);

            $mensalidade = $mensalidadeSession;

            $urlToSave = public_path('/pdf/');

            $pdfFileName = $mensalidadeSession['recibo'] . '-' . str_slug($mensalidadeSession['atleta'], '-') . '.pdf';
            $pdf = PDF::loadView('panel.pdf.recibo-mensalidade', compact('mensalidade', 'pdfFileName', 'valueExt', 'date'));
            $pdf->save($urlToSave . $pdfFileName);

            $urlPdf = $urlToSave . $pdfFileName;

            $mailSubmit = $request->session()->get('mailSubmit');

            if ($mailSubmit == 1) {
                Mail::to($atletaSession->email)->send(new AthleteMensalidade($urlPdf, $mensalidadeSession['ref_mes']));
            }

            return redirect(route('athlete.mensalidades', ['id' => $mensalidadeSession['athlete_id']]))->with('success', 'Sucesso ao adicionar mensalidade!');
        } else {
            $request->session()->forget('mensalidade');
            $request->session()->forget('cashHistory');
            DB::rollBack();
            return redirect()->back()->with('error', 'Algo deu errado, tente novamente');
        }

    }

    public function storeMensalidadeCartao(Athlete $athlete, CartaoMovimento $cartaoMovimentoClass, Request $request, Mensalidade $mensalidades, CashHistory $cashHistory, Monetary $monetary)
    {
        $dataForm = $request->except('_token');


        $mensalidadeSession = $request->session()->get('mensalidade');
        $cashHistorySession = $request->session()->get('cashHistory');
        $atletaSession = $request->session()->get('atleta');
        $atleta = $request->session()->get('atletaUpdate');

        $bandeira = Bandeira::find($dataForm['id_bandeira']);

        $operadora = $bandeira->card;

        $dataForm['valor'] = str_replace('.', '', $dataForm['valor']);
        $dataForm['valor'] = str_replace(',', '.', $dataForm['valor']);

        $valorParcela = $dataForm['valor'] / $dataForm['NParcelas'];

        $valorLiquido = $valorParcela - ($valorParcela * ($bandeira->taxa / 100));

        $now = Date::now();

        $i = 1;

        DB::beginTransaction();

        while ($i <= $dataForm['NParcelas']) {
            $now->add($bandeira->dias . ' days');
            $cartaoMovimento['CodigoOperadora'] = $operadora->id;
            $cartaoMovimento['bandeira_id'] = $bandeira->id;
            $cartaoMovimento['bandeira'] = $bandeira->nome;
            $cartaoMovimento['tipo'] = $bandeira->tipo;
            $cartaoMovimento['entrada'] = 1;
            $cartaoMovimento['cv'] = $dataForm['cv'];
            $cartaoMovimento['NParcelas'] = $dataForm['NParcelas'];
            $cartaoMovimento['parcela'] = $i;
            $cartaoMovimento['valor'] = $valorParcela;
            $cartaoMovimento['taxa'] = $bandeira->taxa;
            $cartaoMovimento['valor_liquido'] = $valorLiquido;
            $cartaoMovimento['previsao'] = $now->format('Y-m-d');
            $cartaoMovimento['liquidado'] = 0;
            $cartaoMovimento['documento'] = $mensalidadeSession['recibo'] . '/' . $i;
            $cartaoMovimento['recibo'] = 1;
            $cartaoMovimentoClass->create($cartaoMovimento);
            $i++;
        }

        $insertHistory = $cashHistory->create($cashHistorySession);

        $insertMensalidade = $mensalidades->create($mensalidadeSession);

        $updateAtleta = $athlete->where('id', $atletaSession->id)->update($atleta);

        if ($insertHistory && $insertMensalidade && $updateAtleta) {
            $request->session()->forget('mensalidade');
            $request->session()->forget('cashHistory');
            DB::commit();
            $dateWeekDay = ucfirst(Date::now()->format('l'));
            $dateDay = Date::now()->format('j');
            $dateMonth = ucfirst(Date::now()->format('F'));
            $dateYear = Date::now()->format('Y');

            $date = $dateWeekDay.', '.$dateDay.' de '.$dateMonth.' de '.$dateYear;

            $valor = $mensalidadeSession['amount'];

            $valueExt = $monetary->extenso($valor, true);

            $mensalidade = $mensalidadeSession;

            $urlToSave = public_path('/pdf/');

            $pdfFileName = $mensalidadeSession['recibo'] . '-' . str_slug($mensalidadeSession['atleta'], '-') . '.pdf';
            $pdf = PDF::loadView('panel.pdf.recibo-mensalidade', compact('mensalidade', 'pdfFileName', 'valueExt', 'date'));
            $pdf->save($urlToSave . $pdfFileName);

            $urlPdf = $urlToSave . $pdfFileName;

            $mailSubmit = $request->session()->get('mailSubmit');

            if($mailSubmit == 1){
                Mail::to($atletaSession->email)->send(new AthleteMensalidade($urlPdf, $mensalidadeSession['ref_mes']));
            }
            return redirect(route('athlete.mensalidades', ['id' => $mensalidadeSession['athlete_id']]))->with('success', 'Sucesso ao adicionar mensalidade!');
        } else {
            $request->session()->forget('mensalidade');
            $request->session()->forget('cashHistory');
            DB::rollBack();
            return redirect()->back()->with('error', 'Algo deu errado, tente novamente');
        }
    }

    public function search(Request $request, Mensalidade $mensalidade)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        if (!isset($dataForm['paginate'])) {
            $dataForm['paginate'] = 15;
        }

        //dd($dataForm);

        $mensalidades = $mensalidade->search($dataForm, $dataForm['paginate']);

        $totalRegister = count($mensalidades);

        $total = $mensalidades->sum('amount');

        return view('panel.athlete.mensalidade.index', compact('mensalidades', 'dataForm', 'totalRegister', 'total'));

    }

    public function searchAthlete(Request $request, Mensalidade $mensalidade, $id)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('fin-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        if (!isset($dataForm['paginate'])) {
            $dataForm['paginate'] = 15;
        }

        $athlete = Athlete::find($id);

        $dataForm['athlete_id'] = $id;

        $mensalidades = $mensalidade->search($dataForm, $dataForm['paginate']);

        $totalRegister = count($mensalidades);

        $total = $mensalidades->sum('amount');

        return view('panel.athlete.mensalidade.athlete-index', compact('athlete', 'mensalidades', 'dataForm', 'totalRegister', 'total'));
    }

    public function downloadPdf($id)
    {

        $mensalidade = Mensalidade::where('recibo', $id)->first();

        $pdfFileName = $mensalidade['recibo'] . '-' . str_slug($mensalidade['atleta'], '-') . '.pdf';

        $file = public_path('/pdf/') . $pdfFileName;

        $headers = array(
            'Content-Type: application/pdf',
        );

        return response()->download($file);
    }

    public function sendPdf($id, Request $request)
    {

        $dataForm = $request->except('_token');

        $mensalidade = Mensalidade::find($id);

        $atleta = Athlete::find($mensalidade['athlete_id']);

        $pdfFileName = $mensalidade['recibo'] . '-' . str_slug($mensalidade['atleta'], '-') . '.pdf';

        $file = public_path('/pdf/') . $pdfFileName;


        if (isset($dataForm['email'])) {
            Mail::to($dataForm['email'])->send(new AthleteMensalidade($file, $mensalidade['ref_mes']));
        } else {
            Mail::to($atleta->email)->send(new AthleteMensalidade($file, $mensalidade['ref_mes']));
        }

        return redirect()->back()->with('success', 'E-Mail enviado com sucesso');

    }

    public function inadimplentes()
    {
        $atletas = Athlete::where('adimplente', 0)->paginate(15);

        return view('panel.athlete.inadimplentes', compact('atletas'));
    }

}
