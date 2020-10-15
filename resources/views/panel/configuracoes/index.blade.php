@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Configuração de envio de email</h1>
@stop

@section('content')
    <div class="main-box">
        <div class="box-body">
            @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{session('error')}}
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{session('success')}}
                </div>
            @endif
            <form action="{{route('settings.update')}}" method="post">
                {{csrf_field()}}
                <div class="form-group" class="col-lg-6">
                    <label>Possibilita baixa com caixa fechado?
                        <small><b>(se essa opção estiver marcada, quando for lançada uma mensalidade com o caixa fechado o sistema lançará uma conta a receber para o atleta)</b></small>
                    </label>
                    <select class="form-control" name="baixa_mensalidade_caixa_fechado">
                        @if(isset($data->baixa_mensalidade_caixa_fechado) && $data->baixa_mensalidade_caixa_fechado == 1)
                            <option value="1" selected>Sim</option>
                        @else
                            <option value="1">Sim</option>
                        @endif
                            @if(isset($data->baixa_mensalidade_caixa_fechado) && $data->baixa_mensalidade_caixa_fechado == 0)
                                <option value="0" selected>Não</option>
                            @else
                                <option value="0">Não</option>
                            @endif
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2">Salvar</button>
            </form>
        </div>
    </div>
@stop