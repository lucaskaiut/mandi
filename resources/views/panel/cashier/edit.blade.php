@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Caixas</h1>
@stop

@section('content')
    <div class="main-box">
        <div class="col-md-12">
            <form method="POST" action="{{route('cashier.update', ['id' => $cashier->id])}}">
                {{csrf_field()}}
                <div class="form-group col-md-6 col-lg-8">
                    <label for="exampleInputEmail1">Caixa</label>
                    <input type="text" name="name" class="form-control" value="{{$cashier->name}}"
                           @cannot('fin-edit') readonly="" @endcannot>
                </div>
                @can('fin-edit')
                    <button type="submit" class="btn btn-success col-md-2 col-lg-1"
                            style="margin-top: 24px; margin-right: 5px;">Gravar
                    </button>
                @endcan
            </form>
            <a href="{{route('cashier.index')}}">
                <button class="btn btn-danger col-md-2 col-lg-1" style="margin-top: 24px;">Cancelar</button>
            </a>
        </div>
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
                                            class="fa fa-trash delete   "></i> Excluir</a>
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
    <script>
        $(document).ready(function () {
            $(".delete").click(function () {
                if (!confirm("Tem certeza que deseja apagar esse caixa?")) {
                    return false;
                }
            });
        });
    </script>
@stop