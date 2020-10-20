@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Adicionar Operadora</h1>
@stop

@section('content')
    <div class="main-box">
        <div class="box-body">
            <form method="POST" action="{{route('card.update', ['id' => $card->id])}}">
                {{csrf_field()}}
                <div class="col-lg-12">
                    <div class="form-group col-lg-8">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="nome" value="{{$card->nome}}">
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Número do estabelecimento</label>
                        <input type="text" data-mask="99999999999999999" class="form-control" name="estabelecimento" value="{{$card->estabelecimento}}">
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary col-lg-2">Cadastrar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop