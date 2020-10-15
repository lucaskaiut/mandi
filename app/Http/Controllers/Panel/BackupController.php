<?php

namespace App\Http\Controllers\Panel;

use App\Mail\BackupMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Jenssegers\Date\Date;

class BackupController extends Controller
{

    public function index()
    {
        return view('panel.backup.index');
    }

    public function backupMysql(Request $request)
    {
        #carrega os dados de conexão de acordo com as configurações do arquivo .env
        $dbhost = env('DB_HOST');
        $dbuser = env('DB_USERNAME');
        $dbpass = env('DB_PASSWORD');
        $dbname = env('DB_DATABASE');
        $path = public_path('/backup');

        $dataForm = $request->except('_token');

        #carrega a data atual
        $now = Date::now()->format('YmdHis');

        #gera o nome do arquivo a partir da data
        $backupfile = 'backup_' . $now . '.sql';

        #executa o comando que gera o backup
        system("mysqldump -h $dbhost -u $dbuser -p'$dbpass' --lock-tables $dbname > $path/$backupfile");

        #carrega o caminho do arquivo
        $file = $path.'/'.$backupfile;

        #valida se é pra enviar por email, fazer o download ou os dois
        if(isset($dataForm['download']) && isset($dataForm['sendMail'])) {
            Mail::to($dataForm['email'])->send(new BackupMail($file));
            return response()->download($file);
        } elseif(isset($dataForm['download'])) {
            return response()->download($file);
        } elseif(isset($dataForm['sendMail'])){
            Mail::to($dataForm['email'])->send(new BackupMail($file));
            return redirect()->back()->with('success', 'Arquivo enviado com sucesso');
        } else {
            return response()->download($file);
        }

    }

    public function indexRestore()
    {
        return view('panel.backup.index-restore');
    }

    public function restoreMysql(Request $request)
    {
        $dbhost = env('DB_HOST');
        $dbuser = env('DB_USERNAME');
        $dbpass = env('DB_PASSWORD');
        $dbname = env('DB_DATABASE');

        $file = $request->file('backupFile');

        #executa o comando que restaura o banco de dados
        system("mysql -h $dbhost -u $dbuser -p'$dbpass' $dbname < $file");

        return redirect()->back()->with('success', 'Backup restaurado com sucesso');
    }
}
