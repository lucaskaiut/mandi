@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Contas a pagar Vencidas</h1>
@stop

@section('content')
    <div class="box-header">
    </div>
    <div class="box-body">
        <div class="main-box">
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
            <table id="example2" class="table table-bordered table-hover" style="margin-top: 10px;">
                <thead>
                <tr>
                    <th>Sequência</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Lançamento</th>
                    <th>Vencimento</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse($invoices as $invoice)
                    <tr style="color: #dd4b39;">
                        <td>{{$invoice->id}}</td>
                        <td>{{$invoice->descricao}}</td>
                        <td>R${{number_format($invoice->valor_pendente, 2, ',', '.')}}</td>
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
                    <p>Não há registro para exibição</p>
                @endforelse
                </tbody>
            </table>
            <div style="padding: 5px;">
                <div style="padding: 5px; overflow: hidden;">
                    <div class="pull-left" style="padding: 5px;">
                        @if($totalRegister == 1)
                            <label> {{$totalRegister}} registro encontrado</label>
                        @else
                            <label> {{$totalRegister}} registros encontrados</label>
                        @endif
                    </div>
                    <div class="pull-right" style="padding: 5px;">
                        <div>
                            <label>Total: </label>R${{number_format($total, 2, ',', '.')}}
                        </div>
                    </div>
                </div>
                {!! $invoices->links() !!}
            </div>
        </div>
    </div>
@stop