@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <div class="form-group title-h1">
        <h1>Contas Bancárias</h1>
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
                    {{session('error')}}
                </div>
            @endif
            @if($op == 'accountToCashier')
                <form method="POST" action="{{route('account.cashier.transfer.store', ['id' => $cashier->id])}}">
                    @elseif($op == 'cashierToAccount')
                        <form method="POST" action="{{route('cashier.account.transfer.store', ['id' => $cashier->id])}}">
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
                    <div class="form-group col-md-1">
                        <label>Valor</label>
                        <input type="text" class="form-control" name="valor" placeholder="Valor">
                    </div>
                    <div class="form-group col-md-2">
                        <label>Descrição</label>
                        <input type="text" class="form-control" name="descricao" placeholder="Descrição">
                    </div>
                    <div class="form-group col-md-2">
                        <label>Documento</label>
                        <input type="text" class="form-control" name="documento" placeholder="Documento">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary col-md-1">Confirmar</button>
                    </div>
                </form>
        </div>
    </div>
@stop