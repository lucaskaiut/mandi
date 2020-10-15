@extends('adminlte::page')

@push('https://raw.githubusercontent.com/igorescobar/jQuery-Mask-Plugin/master/dist/jquery.mask.min.js')

    @section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Caixa Principal</h1>
@stop

@section('content')
    <div class="main-box col-md-12">
        @if($mainCashier == null)
            <div class="alert alert-danger">
                <p>Não há caixa cadastrado</p>
            </div>
        @elseif($mainCashier->status == 'Fechado')
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
            <div class="alert alert-danger">
                <p>O caixa está fechado</p>
            </div>
            @can('fin-edit')
                <button class="btn btn-primary" data-toggle="modal" data-target="#openCashier">Abrir caixa</button>
            @endcan
            <a href="javascript:window.history.go(-1)">
                <button class="btn btn-custom">Voltar</button>
            </a>
        @else
            <div class="box-body">
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
                    <div class="col-md-4 col-xs-12">
                        <!-- small box -->
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3>R${{number_format($saldo, 2, ',', '.')}}</h3>

                                <p>Saldo Total em dinheiro</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-cash"></i>
                            </div>
                            <a href="{{route('cashier.history', ['id' => $mainCashier->id])}}"
                               class="small-box-footer">
                                Histórico de movimentações <i
                                        class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endcan
                @can('fin-edit')
                    <div class="col-md-4">
                        <div class="col-md-12" style="margin-bottom: 12px;">
                            <a href="{{route('account.cashier.transfer', ['id' => $mainCashier->id])}}">
                                <button class="btn btn-custom">Transferir de conta para caixa</button>
                            </a>
                        </div>
                        <div class="col-md-12" style="margin-bottom: 12px;">
                            <a href="{{route('cashier.account.transfer', ['id' => $mainCashier->id])}}">
                                <button class="btn btn-custom">Transferir de caixa para conta</button>
                            </a>
                        </div>
                        <div class="col-md-12">
                            <a href="{{route('cashier.transfer', ['id' => $mainCashier->id])}}">
                                <button class="btn btn-custom">Transferência entre caixas</button>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="col-md-12" style="margin-left: 15px;">
                        <!--<a href="{{route('cashier.close', ['id' => $mainCashier->id])}}"><button  class="btn btn-danger col-md-12" data-toggle="modal" data-target="#favoritesModal">Fechar Caixa</button></a>-->
                            <button class="btn btn-danger col-md-12" data-toggle="modal" data-target="#favoritesModal">
                                Fechar Caixa
                            </button>
                        </div>
                    </div>
                @endcan
            </div>
        @endif
    </div>

    <div class="modal fade" id="favoritesModal" tabindex="-1" role="dialog" aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title title-h1" id="favoritesModalLabel">Fechamento de caixa</h4>
                </div>
                <div class="modal-body">
                    <p><b>Saldo Inicial: </b>R${{number_format($caixaMovimentos['inicial'], 2, ',', '.')}}</p>
                    <hr>
                    <p><b>Entradas: </b>R${{number_format($caixaMovimentos['entradas'], 2, ',', '.')}}</p>
                    <hr>
                    <p><b>Saídas: </b>R${{number_format($caixaMovimentos['saidas'], 2, ',', '.')}}</p>
                    <hr>
                    <p><b>Saldo Final: </b>R${{number_format($caixaMovimentos['final'], 2, ',', '.')}}</p>
                </div>
                <div class="modal-footer">
                    <form action="{{route('cashier.close', ['id' => $mainCashier->id])}}" method="POST">
                        {{csrf_field()}}
                        <label class="pull-left">Imprimir relatório<input type="checkbox" name="print_relatorio"
                                                                          value="1" class="pull-left"></label>
                        <span class="pull-right">
                            <button type="submit" class="btn btn-danger">Fechar Caixa</button>
                        </span>
                    </form>
                    <button type="button" class="btn btn-custom" data-dismiss="modal" style="margin-right: 5px;">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="openCashier" tabindex="-1" role="dialog" aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title title-h1" id="favoritesModalLabel">Abertura de caixa</h4>
                </div>
                <div class="modal-body">
                    @can('fin-edit')
                        <form action="{{route('cashier.open', ['id' => $mainCashier->id])}}" method="POST">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label style="text-align: left;" for="exampleFormControlInput1">Dinheiro</label>
                                <input type="numeric" name="valor" class="form-control" id="exampleFormControlInput1"
                                       placeholder="Dinheiro" required>
                            </div>
                            <span><p><b>Saldo disponível: </b>R${{number_format($saldoDisponivel, 2, ',', '.')}}</p></span>
                            <span class="pull-right">
                            <button type="submit" class="btn btn-primary">Abrir Caixa</button>
                        </span>
                        </form>
                        <button type="button" class="btn btn-custom" data-dismiss="modal" style="margin-right: 5px;">
                            Cancelar
                        </button>
                    @endcan
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <script>
    </script>
@stop