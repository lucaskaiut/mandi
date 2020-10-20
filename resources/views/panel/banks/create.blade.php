@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Cadastro de Conta Bancária</h1>
    @if(isset($errors) && count($errors)>0)
        <div class="alert alert-danger" style="margin-top: 10px;">
            <p>Todos os campos com * são obrigatórios</p>
        </div>
    @endif
@stop

@section('content')
    <div class="main-box">
        <div class="box-body">
            <form method="POST" action="{{route('bank.account.store')}}">
                {{csrf_field()}}
                <div class="col-md-12">
                    <div class="form-group col-md-6">
                        <label for="exampleInputEmail1">Banco</label>
                        <input type="text" class="form-control" name="banco" placeholder="Banco"
                               @cannot('fin-create') disabled="" @endcannot>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="exampleInputEmail1">Agência</label>
                        <input type="text" class="form-control" name="agencia" placeholder="Agência" maxlength="4"
                               @cannot('fin-create') disabled="" @endcannot>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="exampleInputEmail1">Conta</label>
                        <input data-mask="00000000-0" type="text" class="form-control" name="conta" placeholder="Conta"
                               maxlength="20" @cannot('fin-create') disabled="" @endcannot>
                    </div>
                    @can('fin-create')
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary col-md-2">Cadastrar</button>
                        </div>
                    @endcan
                </div>
            </form>
        </div>
    </div>
@stop