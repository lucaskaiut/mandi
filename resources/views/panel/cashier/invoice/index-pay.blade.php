@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Contas a pagar</h1>
    @can('fin-create')
        <div style="
                align-items: center;
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;">
            <a href="{{route('create.invoice.pay')}}">
                <button type="button" class="btn btn-custom">
                    Lançar Conta a Pagar<i class="fa fa-plus-circle" style="padding-left:5px;"></i>
                </button>
            </a>
        </div>
    @endcan
@stop

@section('content')
    <div class="box-header">
    </div>
    <div class="box-body">
        <div class="main-box">
            @if($contasVencidas > 0)
                <a href="">
                    <div class="alert alert-danger" role="alert">
                        Há {{$contasVencidas}} conta(s) vencida(s)!
                    </div>
                </a>
            @endif
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
            @can('fin-list')
                <div class="col-lg-12" style="padding: 5px;">
                    <form class="form-inline" action="{{route('search.pay')}}" method="POST">
                        {{csrf_field()}}
                        <div class="pull-left" style="padding: 5px;">
                            <div class="form-group">
                                <input data-mask="00000" type="text" name="id" class="form-control" style="width: 80px;"
                                       id="inputPassword"
                                       placeholder="Sequência"
                                       @if(isset($dataForm['id'])) value="{{$dataForm['id']}}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputName2">Lancamento</label>
                                <input type="date" name="lancamento_inicio"
                                       @if(isset($dataForm['lancamento_inicio'])) value="{{$dataForm['lancamento_inicio']}}"
                                       @endif class="form-control" id="exampleInputName2">
                                <input type="date" name="lancamento_fim"
                                       @if(isset($dataForm['lancamento_fim'])) value="{{$dataForm['lancamento_fim']}}"
                                       @endif class="form-control" id="exampleInputName2">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputName2">Vencimento</label>
                                <input type="date" name="vencimento_inicio"
                                       @if(isset($dataForm['vencimento_inicio'])) value="{{$dataForm['vencimento_inicio']}}"
                                       @endif class="form-control" id="exampleInputName2">
                                <input type="date" name="vencimento_fim"
                                       @if(isset($dataForm['vencimento_fim'])) value="{{$dataForm['vencimento_fim']}}"
                                       @endif class="form-control" id="exampleInputName2">
                            </div>
                        </div>

                        <div class="pull-right" style="padding: 5px;">
                            <label>Itens por página</label>
                            <div class="form-group">
                                <input type="numeric" name="totalPaginate" class="form-control" style="width: 50px;"
                                       id="inputPassword"
                                       @if(isset($dataForm)) value="{{$dataForm['totalPaginate']}} @endif">
                            </div>
                            <label>
                                <input class="form-check-input" type="radio" name="quitada" id="exampleRadios1"
                                       value="0"
                                       @if($quitada == 0) checked @endif>

                                A pagar |
                            </label>
                            <label>
                                <input class="form-check-input" type="radio" name="quitada" id="exampleRadios2"
                                       value="1"
                                       @if($quitada == 1) checked @endif>

                                Quitadas |
                            </label>
                            <label>
                                <input class="form-check-input" type="radio" name="quitada" id="exampleRadios3"
                                       value="2"
                                       @if($quitada == 2) checked @endif>
                                Todas |
                            </label>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Ordenar</label>
                                <select name="orderBy" class="form-control" id="exampleFormControlSelect1">
                                    <option value="id"
                                            @if(isset($dataForm) && $dataForm['orderBy'] == 'id') selected @endif>
                                        Sequência
                                    </option>
                                    <option value="descricao"
                                            @if(isset($dataForm) && $dataForm['orderBy'] == 'descricao') selected @endif>
                                        Descrição
                                    </option>
                                    <option value="valor"
                                            @if(isset($dataForm) && $dataForm['orderBy'] == 'valor') selected @endif>
                                        Valor
                                    </option>
                                    <option value="created_at"
                                            @if(isset($dataForm) && $dataForm['orderBy'] == 'created_at') selected @endif>
                                        Lançamento
                                    </option>
                                    <option value="vencimento"
                                            @if(isset($dataForm) && $dataForm['orderBy'] == 'vencimento') selected @endif>
                                        Vencimento
                                    </option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            @endcan
            <table id="example2" class="table table-bordered table-hover" style="margin-top: 10px;">
                <thead>
                <tr>
                    <th>Sequência</th>
                    <th>Descrição</th>
                    <th>Valor Original</th>
                    <th>Valor Pendente</th>
                    <th>Quitada</th>
                    <th>Lançamento</th>
                    <th>Vencimento</th>
                    <th>Ações</th>
                </tr>
                </thead>
                @can('fin-list')
                    <tbody>
                    @forelse($invoices as $invoice)
                        <tr @if($invoice->vencimento < $today && $invoice->quitada == 0)
                            style="color: #dd4b39;"
                            @elseif($invoice->quitada == 1)
                            style="color: #00a60d;"
                                @endif>
                            <td>{{$invoice->id}}</td>
                            <td>{{$invoice->descricao}}</td>
                            <td>R${{number_format($invoice->valor_original, 2, ',', '.')}}</td>
                            <td>R${{number_format($invoice->valor_pendente, 2, ',', '.')}}</td>
                            <td>@if($invoice->quitada == 1) Sim @else Não @endif</td>
                            <td>{{ date( 'd/m/Y' , strtotime($invoice->created_at))}}</td>
                            <td>{{ date( 'd/m/Y' , strtotime($invoice->vencimento))}}</td>
                            @if($invoice->quitada == 0)
                                <td class="actions">
                                    @can('fin-edit')
                                        <a class="btn btn-success btn-xs"
                                           href="{{route('payment.invoice.pay', ['id' => $invoice->id])}}"><i
                                                    class="fa fa-dollar"></i> Pagar</a>
                                        <a class="btn btn-primary btn-xs" href="" data-toggle="modal"
                                           data-target="#delete-modal"><i class="fa fa-edit"></i> Editar</a>
                                    @endcan
                                </td>
                            @else
                                <td>----</td>
                            @endif
                        </tr>
                    @empty
                        @if(isset($dataForm))
                            <div class="alert alert-danger" role="alert" style="margin-top: 60px; position: relative;">
                                <p>Não há registro com esses filtros</p>
                            </div>
                        @endif
                    @endforelse
                    </tbody>
                @endcan
            </table>
            <div style="padding: 5px;">
                <div style="padding: 5px; overflow: hidden;">
                    @can('fin-list')
                        <div class="pull-left" style="padding: 5px;">
                            @if($totalRegister == 1)
                                <label> {{$totalRegister}} registro encontrado | </label>
                            @else
                                <label> {{$totalRegister}} registros encontrados | </label>
                            @endif
                            Legendas:
                            <b style="color: #dd4b39">Vencida </b><b>|</b>
                            <b style="color: #00a60d">Quitada</b>
                        </div>
                        <div class="pull-right" style="padding: 5px;">
                            <div>
                                <label>Total: </label>R${{number_format($total, 2, ',', '.')}} |
                                <label>Pago: </label>R${{number_format($totalQuitadas, 2, ',', '.')}} |
                                <label>Vencido: </label>R${{number_format($totalVencidas, 2, ',', '.')}}
                            </div>
                        </div>
                        @endcan
                </div>
                @if(isset($dataForm))
                    {!! $invoices->appends($dataForm)->links() !!}
                @else
                    {!! $invoices->links() !!}
                @endif
            </div>
        </div>
    </div>
@stop