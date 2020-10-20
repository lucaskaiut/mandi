@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1>Permissões de {{$user->name}}</h1>
@stop

@section('content')
    @can('user-edit')
        <form action="{{route('user.permissions.confirm', ['id' => $user->id])}}" method="POST">
            {{csrf_field()}}
            <input type="hidden" value="{{$user->id}}">
            <div class="col-md-4">
                <h3>Usuários</h3>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleUserPermission[0]}}" @if(in_array('user-list', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleUserPermission[0] == 'user-list')
                            Ver Usuário @endif</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleUserPermission[1]}}" @if(in_array('user-create', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleUserPermission[1] == 'user-create')
                            Cadastrar Usuário @endif</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleUserPermission[2]}}" @if(in_array('user-edit', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleUserPermission[2] == 'user-edit')
                            Editar Usuário @endif</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleUserPermission[3]}}" @if(in_array('user-delete', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleUserPermission[3] == 'user-delete')
                            Apagar Usuário @endif</label>
                </div>
            </div>
            <div class="col-md-4">
                <h3>Atletas</h3>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleAthletePermission[0]}}" @if(in_array('athlete-list', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleAthletePermission[0] == 'athlete-list')
                            Ver Atletas @endif</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleAthletePermission[1]}}"
                           @if(in_array('athlete-create', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label"
                           for="exampleCheck1">@if($roleAthletePermission[1] == 'athlete-create')
                            Cadastrar Atleta @endif</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleAthletePermission[2]}}" @if(in_array('athlete-edit', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleAthletePermission[2] == 'athlete-edit')
                            Editar Atleta @endif</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleAthletePermission[3]}}"
                           @if(in_array('athlete-delete', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label"
                           for="exampleCheck1">@if($roleAthletePermission[3] == 'athlete-delete')
                            Apagar Atleta @endif</label>
                </div>
            </div>
            <div class="col-md-4">
                <h3>Financeiro</h3>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleFinPermission[0]}}" @if(in_array('fin-list', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleFinPermission[0] == 'fin-list')
                            Ver Financeiro @endif</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleFinPermission[1]}}" @if(in_array('fin-create', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleFinPermission[1] == 'fin-create')
                            Cadastrar Financeiro @endif</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleFinPermission[2]}}" @if(in_array('fin-edit', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleFinPermission[2] == 'fin-edit')
                            Editar Financeiro @endif</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleFinPermission[3]}}" @if(in_array('fin-delete', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleFinPermission[3] == 'fin-delete')
                            Apagar Financeiro @endif</label>
                </div>
            </div>
            <div class="col-md-4">
                <h3>Empresa</h3>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleFinPermission[0]}}" @if(in_array('fin-list', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleFinPermission[0] == 'fin-list')
                            Ver Empresa @endif</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleFinPermission[1]}}" @if(in_array('fin-create', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleFinPermission[1] == 'fin-create')
                            Cadastrar Empresa @endif</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleFinPermission[2]}}" @if(in_array('fin-edit', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleFinPermission[2] == 'fin-edit')
                            Editar Empresa @endif</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="permissions[]"
                           value="{{$roleFinPermission[3]}}" @if(in_array('fin-delete', $userPermission)) checked
                           @endif @if($user->id == 1) readonly @endif>
                    <label class="form-check-label" for="exampleCheck1">@if($roleFinPermission[3] == 'fin-delete')
                            Apagar Empresa @endif</label>
                </div>
            </div>
            @if($user->id != 1)
                <button type="submit" class="btn btn-primary">Confirmar</button>
            @endif
        </form>
    @endcan
@stop
