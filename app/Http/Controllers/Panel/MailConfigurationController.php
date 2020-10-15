<?php

namespace App\Http\Controllers\Panel;

use App\Models\Panel\MailConfiguration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class MailConfigurationController extends Controller
{
    public function index(MailConfiguration $mailConfiguration)
    {
        $mailConfig = $mailConfiguration->where('id', 1)->first();

        return view('panel.configuracoes.mail', compact('mailConfig'));
    }

    public function update(Request $request, MailConfiguration $mailConfiguration)
    {
        $dataForm = $request->except('_token');

        if(!isset($dataForm['mail_encryption'])){
            $dataForm['mail_encryption'] = 0;
        }
        if ($dataForm['mail_encryption'] == 1){
            $dataForm['mail_encryption'] = 'ssl';
        } else {
            $dataForm['mail_encryption'] = 'null';
        }

        $path = base_path('.env');

        $oldHost = env('MAIL_HOST');
        $oldPort = env('MAIL_PORT');
        $oldUser = env('MAIL_USERNAME');
        $oldPass = env('MAIL_PASSWORD');
        $oldEncp = env('MAIL_ENCRYPTION');

        if (file_exists($path)) {
            file_put_contents( $path, str_replace( 'MAIL_HOST'.'='.$oldHost, 'MAIL_HOST'.'='.$dataForm['mail_host'], file_get_contents($path) ) );
            file_put_contents( $path, str_replace( 'MAIL_PORT'.'='.$oldPort, 'MAIL_PORT'.'='.$dataForm['mail_port'], file_get_contents($path) ) );
            file_put_contents( $path, str_replace( 'MAIL_USERNAME'.'='.$oldUser, 'MAIL_USERNAME'.'='.$dataForm['mail_username'], file_get_contents($path) ) );
            file_put_contents( $path, str_replace( 'MAIL_PASSWORD'.'='.$oldPass, 'MAIL_PASSWORD'.'='.$dataForm['mail_password'], file_get_contents($path) ) );
            file_put_contents( $path, str_replace( 'MAIL_ENCRYPTION'.'='.$oldEncp, 'MAIL_ENCRYPTION'.'='.$dataForm['mail_encryption'], file_get_contents($path) ) );
        }

        if(count($mailConfiguration->all()) == 0){
            $insert = $mailConfiguration->create($dataForm);
            if($insert){
                return redirect(route('config.mail'))->with('success', 'Configuração salva com sucesso');
            } else {
                return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
            }
        } else {
            $update = $mailConfiguration->where('id', 1)->update($dataForm);
            if($update){
                return redirect(route('config.mail'))->with('success', 'Configuração salva com sucesso');
            } else {
                return redirect()->back()->with('error', 'Algo deu errado. Tente novamente');
            }
        }

    }
}
