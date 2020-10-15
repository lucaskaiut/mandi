@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <div class="form-group">
        <h1 class="title-h1">Mensalidades</h1>
    </div>
@stop

@section('content')
    <div class="box-header">
    </div>
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
            @if(count($mensalidades) == 0)
                <div class="alert alert-danger">
                    <p>Não há dados para exibição</p>
                </div>
            @endif
            @can('fin-list')
                <div class="col-lg-12" style="padding: 5px;">
                    <form class="" action="{{route('search.mensalidade')}}" method="POST">
                        {{csrf_field()}}
                        <div class="form-group col-lg-1 col-md-6 col-form-label" style="padding-left: 0;">
                            <label>Recibo</label>
                            <input data-mask="00000" type="text" class="form-control" name="id" placeholder="Nº"
                                   @if(isset($dataForm['id'])) value="{{$dataForm['id']}}" @endif>
                        </div>
                        <div class="form-group col-lg-5 col-md-6 col-form-label">
                            <label>Nome do atleta</label>
                            <input type="text" class="form-control" name="atleta" placeholder="Nome"
                                   @if(isset($dataForm['atleta'])) value="{{$dataForm['atleta']}}" @endif>
                        </div>
                        <div class="form-group col-lg-3 col-md-4">
                            <label>Lançamento Inicio</label>
                            <input type="date" class="form-control" name="lancamento_inicio"
                                   @if(isset($dataForm['lancamento_inicio'])) value="{{$dataForm['lancamento_inicio']}}" @endif>
                        </div>
                        <div class="form-group col-lg-3 col-md-4">
                            <label>Lançamento Fim</label>
                            <input type="date" class="form-control" name="lancamento_fim"
                                   @if(isset($dataForm['lancamento_fim'])) value="{{$dataForm['lancamento_fim']}}" @endif>
                        </div>
                        <div class="form-group col-lg-3 col-md-4" style="padding-left: 0;">
                            <label>Pagamento Inicio</label>
                            <input type="date" class="form-control" name="pagamento_inicio"
                                   @if(isset($dataForm['pagamento_inicio'])) value="{{$dataForm['pagamento_inicio']}}" @endif>
                        </div>
                        <div class="form-group col-lg-3 col-md-4">
                            <label>Pagamento Fim</label>
                            <input type="date" class="form-control" name="pagamento_fim"
                                   @if(isset($dataForm['pagamento_fim'])) value="{{$dataForm['pagamento_fim']}}" @endif>
                        </div>
                        <div class="form-group col-lg-2">
                            <label>Itens por página</label>
                            <input data-mask="00" type="numeric" name="paginate" class="form-control"
                                   style="width: 50px;" id="inputPassword"
                                   @if(isset($dataForm['paginate'])) value="{{$dataForm['paginate']}}" @endif>
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="exampleFormControlSelect1">Ordenar</label>
                            <select name="orderBy" class="form-control" id="exampleFormControlSelect1">
                                <option value="id"
                                        @if(isset($dataForm) && $dataForm['orderBy'] == 'id') selected @endif>
                                    Recibo
                                </option>
                                <option value="atleta"
                                        @if(isset($dataForm) && $dataForm['orderBy'] == 'atleta') selected @endif>
                                    Atleta
                                </option>
                                <option value="ref_mes"
                                        @if(isset($dataForm) && $dataForm['orderBy'] == 'ref_mes') selected @endif>
                                    Mês
                                </option>
                                <option value="amount"
                                        @if(isset($dataForm) && $dataForm['orderBy'] == 'amount') selected @endif>
                                    Valor
                                </option>
                                <option value="created_at"
                                        @if(isset($dataForm) && $dataForm['orderBy'] == 'created_at') selected @endif>
                                    Lançamento
                                </option>
                                <option value="pagamento"
                                        @if(isset($dataForm) && $dataForm['orderBy'] == 'pagamento') selected @endif>
                                    Pagamento
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-top: 25px;">
                            Filtrar
                        </button>
                    </form>
                </div>
            @endcan
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Recibo</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Atleta</th>
                    <th scope="col">Correspondente a</th>
                    <th scope="col">Lançamento</th>
                    <th scope="col">Pagamento</th>
                    <th scope="col">RG</th>
                </tr>
                </thead>
                @can('fin-list')
                    <tbody>
                    @foreach($mensalidades as $mensalidade)
                        <tr>
                            <th scope="row">
                                {{$mensalidade->recibo}}</a>
                            </th>
                            <td>R${{ number_format($mensalidade->amount, 2, ',', '.') }}</td>
                            <td>{{$mensalidade->atleta}}</td>
                            <td>{{$mensalidade->ref_mes}}</td>
                            <td>{{ date( 'd/m/Y' , strtotime($mensalidade->created_at))}}</td>
                            <td>{{ date( 'd/m/Y' , strtotime($mensalidade->pagamento))}}</td>
                            <td>{{$mensalidade->rg}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                @endcan
            </table>
            <div style="padding: 5px;">
                <div style="padding: 5px; overflow: hidden;">
                    <div class="pull-left" style="padding: 5px;">
                        @if($totalRegister == 1)
                            <label> {{$totalRegister}} registro encontrado </label>
                        @else
                            <label> {{$totalRegister}} registros encontrados </label>
                        @endif
                    </div>
                    <div class="pull-right" style="padding: 5px;">
                        <div>
                            <label>Total: </label> R${{number_format($total, 2, ',', '.')}}
                        </div>
                    </div>
                </div>
                @if(isset($dataForm))
                    {!! $mensalidades->appends($dataForm)->links() !!}
                @else
                    {!! $mensalidades->links() !!}
                @endif
            </div>
        </div>
    </div>
@stop