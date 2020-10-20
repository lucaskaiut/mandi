@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <div class="form-group title-h1">
        <h1 class="title-h1">Caixas</h1>
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
                <form method="POST" action="{{route('cashier.transfer.store', ['id' => $cashier->id])}}">
                    {{csrf_field()}}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Selecione o caixa</th>
                            <th scope="col">#</th>
                            <th scope="col">Caixa</th>
                            <th scope="col">Saldo</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cashiers as $cashier)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="cashier_id"
                                               id="exampleRadios1"
                                               value="{{$cashier->id}}">
                                    </div>
                                </td>
                                <td>{{$cashier->id}}</td>
                                <td>{{$cashier->name}}</td>
                                <td>{{$cashier->cash_amount}}</td>
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