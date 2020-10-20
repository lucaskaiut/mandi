@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Bandeiras da Operadora {{$operadora->nome}}</h1>
@stop

@section('content')
    <div class="main-box">
        <div class="box-body">
            <div class="col-lg-12">
                <form action="{{route('bandeira.update', ['id' => $bandeira->id])}}" method="POST">
                    {{csrf_field()}}
                    <div class="form-group col-lg-2">
                        <label>Bandeira</label>
                        <input class="form-control col-lg-2" type="text" name="nome" value="{{$bandeira->nome}}">
                    </div>
                    <div class="form-group col-lg-2">
                        <label>Tipo</label>
                        <select class="form-control col-lg-2"name="tipo">
                            <option value="credito">Crédito</option>
                            <option value="debito" @if($bandeira->tipo == 'debito') selected @endif>Débito</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <label>Taxa (%)</label>
                        <input class="form-control col-lg-2" type="text" data-mask="00,00" data-mask-reverse="true" name="taxa" value="{{$bandeira->taxa}}">
                    </div>
                    <div class="form-group col-lg-1">
                        <label>Dia(s)</label>
                        <input class="form-control col-lg-1" type="numeric" name="dias" value="{{$bandeira->dias}}">
                    </div>
                    <button type="submit" class="btn btn-custom col-lg-1" style="margin-top: 24px;">Gravar</button>
                </form>
                    <a href="{{route('card.bandeiras', ['id' => $operadora->id])}}">
                        <button type="button" class="btn btn-danger" style="margin: 24px auto auto 15px;">
                            Cancelar
                        </button>
                    </a>
            </div>
            <div class="col-lg-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Taxa (%)</th>
                        <th scope="col">Dia(s)</th>
                        <th scope="col">Ações</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($bandeiras as $bandeira)
                        <tr>
                            <th scope="row">
                                {{$bandeira->id}}
                            </th>
                            <td>{{$bandeira->nome}}</td>
                            <td>{{$bandeira->tipo}}</td>
                            <td>{{str_replace('.', ',', $bandeira->taxa)}}</td>
                            <td>{{$bandeira->dias}}</td>
                            <td class="actions">
                                <a href="{{route('bandeira.edit', ['id' => $bandeira->id])}}"
                                   class="btn btn-primary btn-xs"><i class="fa fa-edit"></i>
                                    Editar</a>
                                <a class="btn btn-danger btn-xs"
                                   href="{{route('bandeira.delete', ['id' => $bandeira->id])}}">
                                    <i class="fa fa-trash-alt delete"></i> Excluir</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@stop