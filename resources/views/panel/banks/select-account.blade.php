@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <div class="form-group title-h1">
        <h1>Contas Bancárias</h1>
    </div>
@stop

@section('content')
    <div class="box-header">
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{session('success')}}
            </div>
        @endif
    </div>
    <div class="main-box">
        <div class="box-body">
            @if($selectAccount == 'invoicePayment')
                <form method="POST" action="{{route('store.invoice.pay.bank', ['id' => $invoiceDB->id])}}">
                    @elseif($selectAccount == 'mensalidade')
                        <form method="POST"
                              action="{{route('store.mensalidade.bank', ['id' => $mensalidadeSession['athlete_id']])}}">
                            @elseif($selectAccount == 'invoiceReceivement')
                                <form method="POST"
                                      action="{{route('store.invoice.receive.bank', ['id' => $invoiceDB->id])}}">
                                    @endif
                                    {{csrf_field()}}
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col">Selecione a conta</th>
                                            <th scope="col">#</th>
                                            <th scope="col">Banco</th>
                                            <th scope="col">Agência</th>
                                            <th scope="col">Conta</th>
                                            <th scope="col">Saldo</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($bankAccounts as $bankAccount)
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="bank_account"
                                                               id="exampleRadios1"
                                                               value="{{$bankAccount->id}}">
                                                    </div>
                                                </td>
                                                <td>{{$bankAccount->id}}</td>
                                                <td>{{$bankAccount->banco}}</td>
                                                <td>{{$bankAccount->agencia}}</td>
                                                <td>{{$bankAccount->conta}}</td>
                                                <td>R${{number_format($bankAccount->total_amount, 2, ',', '.')}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary col-md-1">Cadastrar</button>
                                </form>
        </div>
    </div>
@stop