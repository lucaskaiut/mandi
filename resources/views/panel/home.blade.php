@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Página Inicial</h1>
@stop

@section('content')
    <div class="main-box">
        <div class="box-body">
            @if(count($aReceber) > 0)
                <div class="col-lg-4 col-xs-12">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>R${{number_format($totalReceber, 2, ',', '.')}}</h3>

                            <p>Total de contas a receber vencidas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-cash"></i>
                        </div>
                        @can('fin-list')<a href="{{route('invoice.areceber.vencidas')}}"
                                           class="small-box-footer">@endcan
                            Ver Contas <i
                                    class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            @endif
            @if(count($aPagar) > 0)
                    <div class="col-lg-4 col-xs-12">
                        <!-- small box -->
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3>R${{number_format($totalPagar, 2, ',', '.')}}</h3>

                                <p>Total de contas a pagar vencidas</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-cash"></i>
                            </div>
                            @can('fin-list')<a href="{{route('invoice.apagar.vencidas')}}"
                                               class="small-box-footer">@endcan
                                Ver Contas <i
                                        class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endif
                @if(count($atletasInadimplentes) > 0)
                    <div class="col-lg-4 col-xs-12">
                        <!-- small box -->
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3>{{count($atletasInadimplentes)}}</h3>

                                <p>Atletas inadimplentes</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-cash"></i>
                            </div>
                            @can('fin-list')<a href="{{route('atletas.inadimplentes.index')}}"
                                               class="small-box-footer">@endcan
                                Ver Atletas <i
                                        class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endif
        </div>
    </div>
@stop