@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <div class="form-group title-h1">
        <h1>Contas Bancárias</h1>
        @can('fin-create')
            <a href="{{route('bank.account.create')}}">
                <button type="button" class="btn btn-custom">
                    Nova Conta<i class="fa fa-user-plus" style="padding-left:5px;"></i>
                </button>
            </a>
        @endcan
    </div>
@stop

@section('content')
    <div class="box-header">
    </div>
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
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Banco</th>
                <th scope="col">Agência</th>
                <th scope="col">Conta</th>
                <th scope="col">Saldo</th>
                <th scope="col">Ações</th>
            </tr>
            </thead>
            @can('fin-list')
                <tbody>
                @foreach($bankAccounts as $bankAccount)
                    <tr>
                        <td>{{$bankAccount->id}}</td>
                        <td>{{$bankAccount->banco}}</td>
                        <td>{{$bankAccount->agencia}}</td>
                        <td>{{$bankAccount->conta}}</td>
                        <td>R${{number_format($bankAccount->total_amount, 2, ',', '.')}}</td>
                        <td class="actions">
                            @can('fin-edit')
                                <a class="btn btn-success btn-xs"
                                   href="{{route('bank.account.edit', ['id' => $bankAccount->id])}}"><i
                                            class="fa fa-edit"></i>
                                    Editar</a>
                            @endcan
                            @can('fin-delete')
                                <a class="btn btn-danger btn-xs"
                                   href="{{route('bank.account.delete', ['id' => $bankAccount->id])}}"><i
                                            class="fa fa-trash delete"></i> Excluir</a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            @endcan
        </table>
    </div>
    <script>
        $(document).ready(function () {
            $(".delete").click(function () {
                if (!confirm("Tem certeza que deseja apagar essa conta?")) {
                    return false;
                }
            });
        });
    </script>
@stop