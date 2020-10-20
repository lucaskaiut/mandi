@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Operadoras de Cartão</h1>
    <a href="{{route('card.create')}}">
        <button type="button" class="btn btn-custom" style="margin-left: 45%;">
            Nova Operadora<i class="fa fa-plus-circle" style="padding-left:5px;"></i>
        </button>
    </a>
@stop

@section('content')
    <div class="main-box">
        <div class="box-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Número do Estabelecimento</th>
                    <th scope="col">Ações</th>
                </tr>
                </thead>

                <tbody>
                @foreach($operadoras as $operadora)
                    <tr>
                        <th scope="row">
                            {{$operadora->id}}
                        </th>
                        <td>{{$operadora->nome}}</td>
                        <td>{{$operadora->estabelecimento}}</td>
                        <td class="actions">
                            <a class="btn btn-success btn-xs"
                               href="{{route('card.bandeiras', ['id' => $operadora->id])}}">
                                <i class="fab fa-cc-visa"></i> Bandeiras</a>
                            <a href="{{route('card.edit', ['id' => $operadora->id])}}"
                               class="btn btn-primary btn-xs"><i class="fa fa-edit"></i>
                                Editar</a>
                            <a class="btn btn-danger btn-xs"
                               href="{{route('card.delete', ['id' => $operadora->id])}}">
                                <i class="fa fa-trash-alt delete"></i> Excluir</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@stop