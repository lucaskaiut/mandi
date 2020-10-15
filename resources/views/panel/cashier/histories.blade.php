@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <div class="form-group">
        <h1 class="title-h1">Histórico de Movimentações</h1>
    </div>
@stop

@section('content')
    <div class="box-header">
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{session('success')}}
            </div>
        @endif
    </div>
    <div class="main-box">
        <div class="box-body">
            @if(count($cashierHistories) == 0)
                <div class="alert alert-danger">
                    <p>Não há dados para exibição!</p>
                </div>
            @endif
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Sequência</th>
                    <th>Referência</th>
                    <th>Documento</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Entrada/Saída</th>
                    <th>Tipo de Transação</th>
                    <th>Data</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cashierHistories as $history)
                    <tr @if($history->entrada == 0)style="color: red;" @else style="color: green;" @endif>
                        <td>{{$history->id}}</td>
                        <td>{{$history->referencia}}</td>
                        <td>{{$history->documento}}</td>
                        <td>{{$history->descricao}}</td>
                        <td>R${{number_format($history->valor, 2, ',', '.')}}</td>
                        <td>@if($history->entrada == 1) Entrada @else Saída @endif</td>
                        <td>{{$history->pag_tipo}}</td>
                        <td>{{ date( 'd/m/Y' , strtotime($history->created_at))}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $cashierHistories->links() !!}
        </div>
    </div>

@stop

