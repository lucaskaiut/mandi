@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <div class="form-group">
        <h1>Usuários cadastrados</h1>
        @can('user-create')
            <a href="{{route('user.create')}}">
                <button type="button" class="btn btn-custom">
                    Novo Usuário<i class="fa fa-user-plus" style="padding-left:5px;"></i>
                </button>
            </a>
        @endcan
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
    <div class="box">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">E-Mail</th>
                <th scope="col">Ações</th>
            </tr>
            </thead>
            <tbody>
            @can('user-list')
                @foreach($users as $user)
                    <tr>
                        <th scope="row">
                            <a class="edit-link" href="{{route('edit.user', ['id' => $user->id])}}">{{$user->id}}</a>
                        </th>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td class="actions">
                            @can('user-edit')
                                <a class="btn btn-success btn-xs"
                                   href="{{route('user.permissions', ['id' => $user->id])}}"><i
                                            class="fa fa-lock"></i> Permissões</a>
                            @endcan
                            @can('user-delete')
                                <a class="btn btn-danger btn-xs" href="" data-toggle="modal"
                                   data-target="#delete-modal"><i
                                            class="fa fa-user-times"></i> Excluir</a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            @endcan
            </tbody>
        </table>
    </div>
@stop