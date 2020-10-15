@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Cartões de Crédito e Débito</h1>
@stop

@section('content')
    <div class="main-box">
        <div class="box-body">
            <div class="col-lg-12">
                <form class="form" action="{{route('cartao.movimentos.search')}}" method="POST">
                    {{csrf_field()}}
                    <div class="form-group col-lg-2">
                        <label>Crédito/Débito</label>
                        <select class="form-control" name="tipo">
                            <option value="">Ambos</option>
                            <option value="credito"
                                    @if(isset($dataForm['tipo']) && $dataForm['tipo'] == 'credito') selected @endif>
                                Crédito
                            </option>
                            <option value="debito"
                                    @if(isset($dataForm['tipo']) && $dataForm['tipo'] == 'debito') selected @endif>
                                Débito
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <label>Entrada/Saída</label>
                        <select class="form-control" name="entrada">
                            <option value="">Todos</option>
                            <option value="1"
                                    @if(isset($dataForm['entrada']) && $dataForm['entrada'] == 1) selected @endif>
                                Entrada
                            </option>
                            <option value="0"
                                    @if(isset($dataForm['entrada']) && $dataForm['entrada'] == 0) selected @endif>Saída
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <label>Liquidado</label>
                        <select class="form-control" name="liquidado">
                            <option value="2" @if(isset($dataForm['liquidado']) && $dataForm['liquidado'] == 2) selected @endif>
                                Todos
                            </option>
                            <option value="1"
                                    @if(isset($dataForm['liquidado']) && $dataForm['liquidado'] == 1) selected @endif>
                                Sim
                            </option>
                            <option value="0"
                                    @if(isset($dataForm['liquidado']) && $dataForm['liquidado'] == 0) selected @endif>
                                Não
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <label>Ordenar</label>
                        <select name="orderBy" class="form-control">
                            <option value="id"
                                    @if(isset($dataForm['orderBy']) && $dataForm['orderBy'] == "id") selected @endif>
                                Sequência
                            </option>
                            <option value="bandeira"
                                    @if(isset($dataForm['orderBy']) && $dataForm['orderBy'] == "bandeira") selected @endif>
                                Bandeira
                            </option>
                            <option value="tipo"
                                    @if(isset($dataForm['orderBy']) && $dataForm['orderBy'] == "tipo") selected @endif>
                                Tipo
                            </option>
                            <option value="valor"
                                    @if(isset($dataForm['orderBy']) && $dataForm['orderBy'] == "valor") selected @endif>
                                Valor da Parcela
                            </option>
                            <option value="taxa"
                                    @if(isset($dataForm['orderBy']) && $dataForm['orderBy'] == "taxa") selected @endif>
                                Taxa
                            </option>
                            <option value="valor_liquido"
                                    @if(isset($dataForm['orderBy']) && $dataForm['orderBy'] == "valor_liquido") selected @endif>
                                Valor Líquido
                            </option>
                            <option value="previsao"
                                    @if(isset($dataForm['orderBy']) && $dataForm['orderBy'] == "previsao") selected @endif>
                                Previsão
                            </option>
                            <option value="dataliquidado"
                                    @if(isset($dataForm['orderBy']) && $dataForm['orderBy'] == "dataliquidado") selected @endif>
                                Liquidado
                            </option>
                            <option value="created_at"
                                    @if(isset($dataForm['orderBy']) && $dataForm['orderBy'] == "created_at") selected @endif>
                                Lançado
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <label>Itens por página</label>
                        <input name="paginate" type="number" class="form-control"
                               @if(isset($dataForm['paginate'])) value="{{$dataForm['paginate']}}" @endif>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" style="margin-top: 24px;">Filtrar</button>
                    </div>
                </form>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th scope="col">Sequência</th>
                    <th scope="col">Bandeira</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Entrada/Saída</th>
                    <th scope="col">CV</th>
                    <th scope="col">Documento</th>
                    <th scope="col">Valor da Parcela</th>
                    <th scope="col">Taxa (%)</th>
                    <th scope="col">Valor líquido</th>
                    <th scope="col">Previsão</th>
                    <th scope="col">Liquidado</th>
                    <th scope="col">Lançado</th>
                    <th scope="col">Ações</th>
                </tr>
                </thead>

                <tbody>
                @foreach($cartaoMovimentos as $movimento)
                    <tr>
                        <th scope="row">
                            {{$movimento->id}}
                        </th>
                        <td>{{$movimento->bandeira}}</td>
                        <td>@if($movimento->tipo == 'credito') Crédito @else Débito @endif</td>
                        <td>@if($movimento->entrada == '1') Entrada @else Saída @endif</td>
                        <td>{{$movimento->cv}}</td>
                        <td>{{$movimento->documento}}</td>
                        <td>{{number_format($movimento->valor, 2, ',', '.')}}</td>
                        <td>{{$movimento->taxa}}</td>
                        <td>{{number_format($movimento->valor_liquido, 2, ',', '.')}}</td>
                        <td>{{ date( 'd/m/Y' , strtotime($movimento->previsao))}}</td>
                        <td>@if($movimento->dataliquidado == null)
                                - @else {{ date( 'd/m/Y' , strtotime($movimento->dataliquidado))}} @endif</td>
                        <td>{{ date( 'd/m/Y' , strtotime($movimento->created_at))}}</td>
                        <td class="actions">
                            <a @if($movimento->liquidado == 1) class="btn btn-success btn-xs disabled" @else class="btn btn-success btn-xs" @endif
                                href="{{route('cartao.movimento.baixar', ['id' => $movimento->id])}}">
                                <i class="fab fa-cc-visa"></i> Baixar</a>
                            <a href="{{route('cartao.movimento.estornar', ['id' => $movimento->id])}}"
                               class="btn btn-primary btn-xs"><i class="fa fa-edit"></i>
                                Estornar</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>
            @if(isset($dataForm))
                {!! $cartaoMovimentos->appends($dataForm)->links() !!}
            @else
                {!! $cartaoMovimentos->links() !!}
            @endif
        </div>
    </div>
@stop