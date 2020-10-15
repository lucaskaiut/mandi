@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')
<style>
    #blanket, #aguarde, #aguardemail {
        position: fixed;
        display: none;
    }

    #blanket {
        left: 0;
        top: 0;
        background-color: #f0f0f0;
        filter: alpha(opacity=65);
        height: 100%;
        width: 100%;
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=65)";
        opacity: 0.65;
        z-index: 9998;
    }

    #aguarde, #aguardemail {
        width: auto;
        height: 30px;
        top: 40%;
        left: 45%;
        background: url('http://i.imgur.com/SpJvla7.gif') no-repeat 0 50%;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        z-index: 9999;
        padding-left: 27px;
    }
</style>
@section('content_header')
    <h1 class="title-h1">Envio de E-Mail</h1>
@stop

@section('content')
    <div class="main-box">
        <div class="box-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <p>{{session('success')}}</p>
                </div>
            @endif
            <form method="POST" action="{{route('custom.mail.send')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="col-md-12">
                    <div class="form-group col-lg-3">
                        <label>Para:</label>
                        <input style="width: 215px;" required type="email" class="form-control" name="receiver[]"
                               placeholder="Destinatário">
                    </div>
                    <div class="form-check col-lg-4" style="margin-top: 30px;">
                        <label class="form-check-label">
                            <input class="form-check-input" name="atletas" type="checkbox" value="1" id="submitMail">
                            Enviar para todos os E-Mails cadastrados
                        </label>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Assunto:</label>
                        <input style="width: 215px;" required type="text" class="form-control" name="subject"
                               placeholder="Assunto">
                    </div>
                    <div class="form-group col-md-12">
                        <label>Corpo do Email</label>
                        <textarea class="form-control rounded-0" name="body" rows="5"></textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Anexo</label>
                        <input type="file" name="file">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary col-md-2">Enviar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="blanket"></div>
    <div id="aguardemail">Enviando e-mail</div>
    <script>
        $(document).ready(function () {
            $('.btn-primary').click(function () {
                $('#aguardemail, #blanket').css('display', 'block');
            });
        });

    </script>
@stop