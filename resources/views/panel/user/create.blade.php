@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1>Cadastro de Usuário</h1>
    @if(isset($errors) && count($errors)>0)
        <div class="alert alert-danger" style="margin-top: 10px;">
            <p>Todos os campos com * são obrigatórios</p>
        </div>
    @endif
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{route('store.user')}}">
            {{csrf_field()}}
            <div class="form-group">
                <label for="exampleInputEmail1">Nome</label>
                <input @cannot('user-create') readonly="" @endcannot  type="text" class="form-control"
                       id="exampleInputEmail1" aria-describedby="emailHelp" name="name" placeholder="Nome">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">E-Mail</label>
                <input @cannot('user-create') readonly="" @endcannot  type="email" class="form-control"
                       id="exampleInputEmail1" aria-describedby="emailHelp" name="email" placeholder="E-Mail">
                <small id="emailHelp" class="form-text text-muted">Nós nunca compartilharemos seu e-mail com ninguém.
                </small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Senha</label>
                <input @cannot('user-create') readonly="" @endcannot  type="password" class="form-control"
                       id="exampleInputPassword1" name="password" placeholder="Senha">
            </div>
            <div class="form-check">
                <input @cannot('user-create') readonly="" @endcannot  type="checkbox" class="form-check-input"
                       id="exampleCheck1" name="super-admin"
                       value="1">
                <label class="form-check-label" for="exampleCheck1"> Administrador </label>
            </div>
            @can('user-create')
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            @endcan
        </form>
    </div>
@stop