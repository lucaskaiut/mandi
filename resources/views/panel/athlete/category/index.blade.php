@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Categorias</h1>
@stop

@section('content')
    <div class="main-box">
        @can('athlete-create')
            <form method="POST" action="{{route('category.store')}}">
                {{csrf_field()}}
                <div class="col-lg-12">
                    <div class="form-group col-lg-10 col-md-8 col-sm-9 col-xs-8">
                        <label for="exampleInputEmail1">Categoria</label>
                        <input required type="text" name="categoria" class="form-control" placeholder="Categoria">
                    </div>
                    <div class="form-group col-lg-1 col-md-2 col-sm-3 col-xs-4">
                        <label for="exampleCheck1">Prefixo</label>
                        <input required type="text" name="prefixo" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success col-lg-1 col-md-2" style="margin-top: 24px;">Gravar
                    </button>
                </div>
            </form>
        @endcan
        <div class="box-body">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Categoria</th>
                    <th>Prefixo</th>
                    <th>Unidade</th>
                    <th>Ações</th>
                </tr>
                </thead>
                @can('athlete-list')
                    <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{$category->id}}</td>
                            <td>{{$category->categoria}}</td>
                            <td>{{$category->prefixo}}</td>
                            <td>{{$category->empresa}}</td>
                            <td class="actions">
                                @can('athlete-edit')
                                    <a class="btn btn-success btn-xs"
                                       href="{{route('category.edit', ['id' => $category->id])}}"><i
                                                class="fa fa-edit"></i> Editar</a>
                                @endcan
                                @can('athlete-delete')
                                    <a class="btn btn-danger btn-xs delete"
                                       href="{{route('category.delete', ['id' => $category->id])}}"><i
                                                class="fa fa-trash"></i> Excluir</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <p>Não há categoria cadastrada</p>
                    @endforelse
                    </tbody>
                @endcan
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $(".delete").click(function(){
                if (!confirm("Tem certeza que deseja apagar essa categoria?")){
                    return false;
                }
            });
        });
    </script>
@stop