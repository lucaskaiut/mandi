@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1>Cadastro de Usuário</h1>
    @if(session('error'))
        <div class="alert alert-error" role="alert">
            {{session('error')}}
        </div>
    @endif
    @if(count($users) > 1)
        <a href="{{route('delete.user', ['id' => $userToEdit->id])}}">
            <button type="button" class="btn btn-danger">
                Apagar Usuário
            </button>
        </a>
    @endif
    @if(isset($errors) && count($errors)>0)
        <div class="alert alert-danger" style="margin-top: 10px;">
            <p>Todos os campos com * são obrigatórios</p>
        </div>
    @endif
@stop

@section('content')
    <div class="container-fluid">
        <form method="POST" action="{{route('update.user', ['id' => $userToEdit->id])}}">
            {{csrf_field()}}
            <input @cannot('user-edit') readonly="" @endcannot  type="hidden" name="id" value="{{$userToEdit->id}}">
            <div class="form-group">
                <label for="exampleInputEmail1">Nome</label>
                <input @cannot('user-edit') readonly="" @endcannot  type="text" class="form-control"
                       id="exampleInputEmail1" aria-describedby="emailHelp" name="name" value="{{$userToEdit->name}}">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">E-Mail</label>
                <input @cannot('user-edit') readonly="" @endcannot  type="email" class="form-control"
                       id="exampleInputEmail1" aria-describedby="emailHelp" name="email" value="{{$userToEdit->email}}">
                <small id="emailHelp" class="form-text text-muted">Nós nunca compartilharemos seu e-mail com ninguém.
                </small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Senha</label>
                <input @cannot('user-edit') readonly="" @endcannot  type="password" class="form-control"
                       id="exampleInputPassword1" name="password" placeholder="Senha">
            </div>
            @can('user-edit')
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            @endcan
        </form>
    </div>
@stop