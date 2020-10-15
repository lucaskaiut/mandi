@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Caixas</h1>
@stop

@section('content')
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
        @can('fin-create')
            <form method="POST" action="{{route('cashier.store')}}">
                {{csrf_field()}}
                <div class="col-md-12">
                    <div class="form-group col-md-10">
                        <label for="exampleInputEmail1">Caixa</label>
                        <input type="text" name="name" class="form-control" placeholder="Caixa">
                    </div>
                    <button type="submit" class="btn btn-success col-md-1" style="margin-top: 1.9%;">Gravar</button>
                </div>
            </form>
        @endcan
        <div class="box-body">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Caixa</th>
                    <th>Usuário</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse($cashiers as $cashier)
                    <tr>
                        <td>{{$cashier->id}}</td>
                        <td>{{$cashier->name}}</td>
                        <td>{{$cashier->user_name}}</td>
                        <td>{{$cashier->status}}</td>
                        <td class="actions">
                            @can('fin-edit')
                                <a class="btn btn-success btn-xs"
                                   href="{{route('cashier.edit', ['id' => $cashier->id])}}"><i
                                            class="fa fa-edit"></i> Editar</a>
                            @endcan
                            @can('fin-delete')
                                <a class="btn btn-danger btn-xs"
                                   href="{{route('cashier.delete', ['id' => $cashier->id])}}"><i
                                            class="fa fa-trash"></i> Excluir</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <p>Não há caixa cadastrado</p>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop