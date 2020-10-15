@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Conta Bancária - {{$bankAccount->banco}}</h1>
    @if(isset($errors) && count($errors)>0)
        <div class="alert alert-danger" style="margin-top: 10px;">
            <p>Todos os campos com * são obrigatórios</p>
        </div>
    @endif
@stop

@section('content')
    <div class="main-box">
        <div class="box-body">
            <form method="POST" action="{{route('bank.account.update', ['id' => $bankAccount->id])}}">
                {{csrf_field()}}
                <div class="col-md-12">
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Banco</label>
                        <input type="text" class="form-control" name="banco" value="{{$bankAccount->banco}}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="exampleInputEmail1">Agência</label>
                        <input type="text" class="form-control" name="agencia" value="{{$bankAccount->agencia}}" maxlength="4">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="exampleInputEmail1">Conta</label>
                        <input data-mask="00000000-0" type="text" class="form-control" name="conta" value="{{$bankAccount->conta}}" maxlength="20">
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary col-md-2">Gravar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop