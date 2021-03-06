@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Desdobramento de cartão</h1>
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
                    {{session('erro')}}
                </div>
            @endif
            <form class="form" action="{{route('store.mensalidade.cartao', ['id' => $athlete->id])}}" method="POST">
                {{csrf_field()}}
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Bandeira</th>
                        <th>Taxa (%)</th>
                        <th>Dias</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bandeiras as $bandeira)
                        <tr>
                            <td><input type="radio" name="id_bandeira" value="{{$bandeira->id}}"></td>
                            <td scope="row">{{$bandeira->nome}}</td>
                            <td scope="row">{{$bandeira->taxa}}</td>
                            <td scope="row">{{$bandeira->dias}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="form-group col-lg-6">
                    <label>NºCV</label>
                    <input class="form-control" type="text" name="cv" data-mask="00000000" placeholder="NºCV">
                </div>
                <input class="form-control" type="hidden" name="valor" value="{{$dataForm['valor_pago']}}">
                <div class="form-group col-lg-3">
                    <label>Parcelas</label>
                    <input class="form-control" type="number" name="NParcelas" placeholder="Parcelas">
                </div>
                <button type="submit" class="btn btn-success" style="margin-top: 24px;">Baixar</button>
            </form>
            <div class="col-lg-12">
                <p style="font-weight: bold;">Valor Recebido:
                    R${{number_format($dataForm['valor_pago'], 2, ',', '.')}}</p>
            </div>
        </div>
    </div>
@stop