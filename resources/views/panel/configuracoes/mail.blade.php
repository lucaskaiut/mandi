@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Configuração de envio de email</h1>
@stop

@section('content')
    <div class="main-box">
        <div class="box-body">
            <form method="POST" action="{{route('config.mail.update')}}">
                {{csrf_field()}}
                <div class="col-md-12">
                    <div class="form-group col-md-12">
                        <label>Servidor de saída</label>
                        <input style="width: 215px;" required type="text" class="form-control" name="mail_host" placeholder="Servidor de saída"
                        @if(isset($mailConfig->mail_host)) value="{{$mailConfig->mail_host}}" @endif>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Porta</label>
                        <input style="width: 215px;" required type="text" class="form-control" name="mail_port" placeholder="Porta"
                               @if(isset($mailConfig->mail_port)) value="{{$mailConfig->mail_port}}" @endif>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Usuário <small>(email utilizado para entrar na conta)</small></label>
                        <input style="width: 215px;" required type="text" class="form-control" name="mail_username" placeholder="Usuário"
                               @if(isset($mailConfig->mail_username)) value="{{$mailConfig->mail_username}}" @endif>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Senha</label>
                        <input style="width: 215px;" required type="password" class="form-control" name="mail_password" placeholder="Senha"
                               @if(isset($mailConfig->mail_password)) value="{{$mailConfig->mail_password}}" @endif>
                    </div>
                    <div class="form-check col-md-12" style="margin-top: 24px;">
                        <input type="checkbox" name="mail_encryption" value="1" class="form-check-input" @if(isset($mailConfig->mail_encryption) && $mailConfig->mail_encryption != null) checked @endif>
                        <label class="form-check-label">Este servidor requer uma conexão segura (SSL) </label>
                    </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary col-md-2">Cadastrar</button>
                        </div>
                </div>
            </form>
        </div>
    </div>
@stop