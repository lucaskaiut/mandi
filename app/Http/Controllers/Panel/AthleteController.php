<?php

namespace App\Http\Controllers\Panel;

use App\Http\Requests\Panel\AthleteFormRequest;
use App\Models\Panel\Category;
use App\Models\Panel\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Panel\Athlete;
use PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AthleteMail;
use Jenssegers\Date\Date;

/*********************************************\
*                                             *
* Classe responsável pela gestão dos atletas  *
*                                             *
\*********************************************/
class AthleteController extends Controller
{

    private $athletePaginate = 15;

    public function index(Athlete $athlete, Category $category)
    {

        #a variável usar está recebendo um objeto do usuário que está logado no momento
        $user = Auth::user();

        #verifica se o usuário tem a permissão 'athlete-list', caso não tenha, encerra e execução do método
        if (!$user->hasPermissionTo('athlete-list')) {
            return 'Você não possui acesso à essa área!';
        }

        #essa variável recebe todas as categorias
        $categories = $category->all();

        #essa variável recebe a seguinte query: SELECT * FROM athletes WHERE active = 1 AND deleted = 0 ORDER BY matricula
        $athletes = $athlete->where('active', 1)->where('deleted', 0)->orderBy('matricula')->paginate($this->athletePaginate);

        #essa variável recebe todos os atletas com a flag 'inativo'
        //$pendingAthletes = count($athlete->pendingAthlete());
        $pendingAthletes = 0;

        #retorna a view /view/panel/athlete/index com todas as variáveis setadas acima
        return view('panel.athlete.index', compact('athletes', 'pendingAthletes', 'categories'));

    }

    public function newAthlete()
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-create')) {
            return 'Você não possui acesso à essa área!';
        }

        #recebe todas as empresas
        $empresas = Company::all();

        #retorna a view /views/newathlete
        return view('newathlete', compact('empresas'));
    }

    public function store(AthleteFormRequest $request, Athlete $athlete)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-create')) {
            return 'Você não possui acesso à essa área!';
        }

        #recupera os dados enviados via request
        $dataForm = $request->except('_token');

        #insere a variável "dataForm" na tabela athletes
        $insert = $athlete->create($dataForm);

        if ($insert) {
            #se o insert funcionar, redireciona para a rota /
            return redirect('/')->with('success', 'Atleta cadastrado com sucesso');
        } else {
            #se não funcionar, redireciona para a última rota acessada
            return redirect()->back()->with('error', 'Não foi possível efetuar o cadastro');
        }

    }

    public function newAthleteConfirmed()
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-create')) {
            return 'Você não possui acesso à essa área!';
        }

        #recebe todas as categorias
        $categories = Category::all();

        #recebe todas as empresas
        $empresas = Company::all();
        if(count($empresas) == 0){
            return redirect()->back()->with('error', 'Para cadastrar um atleta deve haver pelo menos uma empresa cadastrada');
        }

        #retorna a view /panel/athlete/create com as duas variáveis setadas acima
        return view('panel.athlete.create', compact('categories', 'empresas'));
    }

    //public function storeConfirmed(AthleteFormRequest $request, Athlete $athlete)
    public function storeConfirmed(Request $request, Athlete $athlete, BuscaCEP $buscaCEP)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-create')) {
            return 'Você não possui acesso à essa área!';
        }

        #recupera os dados enviados via request
        $dataForm = $request->except('_token');

        #busca a categoria de acordo com a categoria do atleta
        $category = Category::find($dataForm['athlete_category']);

        #recupera o último id e adiciona 1
        $idPrefix = Athlete::max('id')+1;

        #se não houver atleta cadastrado no banco de dados, seta a variável $idPrefix = 0
        if($idPrefix == null){
            $idPrefix = 0;
        }

        #monta a matricula de acordo com o prefixo da categoria + a variável idPrefix
        $dataForm['matricula'] = $category['prefixo'].' - '.$idPrefix;

        #diz que o atleta é ativo
        $dataForm['active'] = 1;

        #carrega a empresa de acordo com a empresa selecionada no formulário
        $empresa = Company::find($dataForm['company_id']);

        #seta o apelido da empresa para o atleta
        $dataForm['empresa'] = $empresa->apelido;

        #insere o array "dataForm" na tabela de atletas
        $dataForm['cep'] = str_replace('-', "", $dataForm['cep']);

        $insert = $athlete->create($dataForm);

        if($insert){
            return redirect(route('athletes'))->with('success', 'Atleta cadastrado com sucesso');
        } else {
            return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
        }
    }

    public function edit($id, Athlete $athlete)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        #carrega o atleta de acordo com o id informado na rota
        $data = $athlete->where('id', $id)->first();

        $categories = Category::all();

        $empresas = Company::all();

        return view('panel.athlete.edit', compact('data', 'categories', 'empresas'));
    }

    public function update(AthleteFormRequest $request, Athlete $athlete)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-edit')) {
            return 'Você não possui acesso à essa área!';
        }

        $dataForm = $request->except('_token');

        $dataForm['active'] = 1;

        $categoryDB = Category::find($dataForm['athlete_category']);

        $dataForm['athlete_category'] = $categoryDB['categoria'];

        #carrega o atleta de acordo com o id passado no metodo anterior
        $athleteForm = $athlete->where('id', $dataForm['id'])->first();

        #monta a matricula de acordo com a categoria do atleta + o id do atleta
        $dataForm['matricula'] = $categoryDB['prefixo'].' - '.$dataForm['id'];

        #carrega a empresa de acordo com o id da empresa
        $empresa = Company::find($dataForm['company_id']);

        $dataForm['empresa'] = $empresa->apelido;

        #atualiza o atelta com o array "dataForm"
        $update = $athlete->where('id', $dataForm['id'])->update($dataForm);

        if ($update) {
            return redirect(route('athletes'))->with('success', 'Atleta atualizado com sucesso');
        } else {
            return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
        }

    }

    public function delete($id, Athlete $athlete)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-delete')) {
            return 'Você não possui acesso à essa área!';
        }

        #apaga o atleta de acordo com o id informado na rota
        $athlete->where('id', $id)->update(['deleted' => 1]);

        return redirect('/painel/atletas')->with('success', 'Atleta deletado com sucesso');
    }

    public function search(Request $request, Athlete $athlete)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $categoria = $request->categoria;

        #se clicar no botão "todos" carrega todos os atletas
        if($categoria == 'Todos'){
            $athletes = $athlete->where('active', 1)->where('deleted', 0)->get();
        } else {
            #se clicar em alguma categoria, carrega o atleta de acordo com a categoria selecionada
            $athletes = $athlete->where('athlete_category', $categoria)->where('active', 1)->where('deleted', 0)->get();
        }

        #retorna um json com os atletas
        return response()->json(['data'=>$athletes]);

    }

    public function pending(Athlete $athlete)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-list')) {
            return 'Você não possui acesso à essa área!';
        }

        #retorna todos os atletas que estão inativos
        $athletes = $athlete->where('active', 0)->where('deleted', 0)->paginate($this->athletePaginate);

        #conta os atletas inativos
        $pendingAthletes = count($athlete->pendingAthlete());

        return view('panel.athlete.pending', compact('athletes', 'pendingAthletes'));
    }

    public function generatePdf($id)
    {

        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-list')) {
            return 'Você não possui acesso à essa área!';
        }

        #carrega o atleta de acordo com o id informado na rota
        $athleteOne = Athlete::find($id);

        #monta o nome do arquivo a partir do nome do atleta
        $pdfFileName = str_slug($athleteOne->name, '-') . '.pdf';

        #carrega a view do PDF passando as variáveis
        $pdf = PDF::loadView('panel.pdf.one-athlete', compact('athleteOne', 'pdfFileName'));

        #retorna o método que gera o PDF na tela
        return $pdf->stream($pdfFileName);
    }

    public function enviarFicha($id)
    {
        $user = Auth::user();

        if (!$user->hasPermissionTo('athlete-list')) {
            return 'Você não possui acesso à essa área!';
        }

        $athleteOne = Athlete::find($id);

        #monta a data atual no formato AAMMDDHHMM
        $date = Date::now()->format('YmdHi');

        #monta o nome do pdf de acordo com o nome do atleta e a variavel "date"
        $pdfFileName = str_slug($athleteOne->name, '-').'-'.$date.'.pdf';

        #seta o caminho para o PDF (/public/pdf/)
        $url = asset('pdf/'.$pdfFileName);

        #carrega a view do pdf passando as variaveis
        $pdf = PDF::loadView('panel.pdf.one-athlete', compact('athleteOne', 'pdfFileName'));

        #salva o pdf
        $pdf->save('pdf/'.$pdfFileName);

        #envia o pdf para o email do atleta buscando o arquivo pela variavel $url
        Mail::to($athleteOne->email)->send(new AthleteMail($url));

        return redirect()->back()->with('success', 'E-Mail enviado com sucesso');

    }

    public function test(Athlete $athlete)
    {
        $athletes = $athlete->all();

        $categories = Category::all();

        return view('panel.company.categoria-empresa', compact('athletes', 'categories'));

    }
}
