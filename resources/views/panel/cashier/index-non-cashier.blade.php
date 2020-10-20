@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Caixa Principal</h1>
@stop

@section('content')
    <div class="main-box col-md-12">
        <div class="box-body">
            @if($cashiers > 0)
                <div class="alert alert-danger">
                    <p>Não há nenhum caixa aberto</p>
                </div>
                <a href="">
                    <button class="btn btn-primary">Abrir caixa</button>
                </a>
                <a href="">
                    <button class="btn btn-custom">Voltar</button>
                </a>
            @else()
                <div class="alert alert-danger">
                    <p>Não há nenhum caixa cadastrado</p>
                </div>
                <a href="{{route('cashier.index')}}">
                    <button class="btn btn-primary">Cadastrar Caixa</button>
                </a>
                <a href="">
                    <button class="btn btn-custom">Voltar</button>
                </a>
            @endif
        </div>
    </div>
@stop