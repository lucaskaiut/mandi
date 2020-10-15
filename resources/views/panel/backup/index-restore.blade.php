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
        width: 500px;
        height: 100px;
        top: 40%;
        left: 45%;
        background: url('https://loading.io/spinners/ellipsis/lg.discuss-ellipsis-preloader.gif') no-repeat 0 50%;
        //background: url('http://i.imgur.com/SpJvla7.gif') no-repeat 0 50%;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        z-index: 9999;
        padding-left: 27px;
    }
</style>
@section('content_header')
    <h1 class="title-h1">Cadastro de Conta Bancária</h1>
    @if(isset($errors) && count($errors)>0)
        <div class="alert alert-danger" style="margin-top: 10px;">
            <p>Todos os campos com * são obrigatórios</p>
        </div>
    @endif
@stop

@section('content')
    <div class="main-box">
        <div class="box-body">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{session('success')}}
                </div>
            @endif
                @if(session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{session('error')}}
                    </div>
                @endif
            <form method="POST" action="{{route('backup.restore')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="col-lg-12">
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Arquivo de Backup</label>
                        <input type="file" name="backupFile">
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary col-md-2">Restaurar Backup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="blanket"></div>
    <div id="aguardemail">Restaurando backup</div>
    <script>
        $(document).ready(function () {
            $('.btn-primary').click(function () {
                $('#aguardemail, #blanket').css('display', 'block');
            });
        });
    </script>
@stop