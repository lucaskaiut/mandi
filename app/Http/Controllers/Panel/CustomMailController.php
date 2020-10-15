<?php

namespace App\Http\Controllers\Panel;

use App\Mail\CustomMail;
use App\Models\Panel\Athlete;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class CustomMailController extends Controller
{
    public function index()
    {
        return view('panel.mail.send.index-mail');
    }

    public function sendMail(Request $request)
    {
        $dataForm = $request->except('_token');

        //dd($dataForm);

        $body = $dataForm['body'];
        if (isset($dataForm['atletas']) && $dataForm['atletas'] == 1) {
            $receiver = Athlete::all('email');
        } else {
            $receiver = $dataForm['receiver'];
        }
        $subject = $dataForm['subject'];

        if ($request->hasFile('file') && $request->file('file')->isValid()) {

            // Define um aleatório para o arquivo baseado no timestamps atual
            $name = uniqid(date('HisYmd'));

            // Recupera a extensão do arquivo
            $extension = $request->file->extension();

            // Define finalmente o nome
            $nameFile = "{$name}.{$extension}";

            $path = storage_path('app/public/temp');

            // Faz o upload:
            $upload = $request->file->storeAs('temp', $nameFile);

            $file = $path . '/' . $nameFile;

        } else {
            $file = null;
        }

        //dd($receiver);

        Mail::bcc($receiver)->send(new CustomMail($subject, $file, $body));

        return redirect()->back()->with('success', 'E-Mail enviado com sucesso');

    }

}
