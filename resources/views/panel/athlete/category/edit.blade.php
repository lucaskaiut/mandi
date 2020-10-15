@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Categorias</h1>
@stop

@section('content')
    <div class="main-box">
        @can('athlete-create')
            <div class="col-md-12">
                <form method="POST" action="{{route('category.update', ['id' => $category->id])}}">
                    {{csrf_field()}}
                    <div class="form-group col-lg-8 col-md-5 col-sm-10">
                        <label for="exampleInputEmail1">Categoria</label>
                        <input required type="text" name="categoria" class="form-control"
                               value="{{$category->categoria}}" @cannot('athlete-edit') disabled="" @endcannot>
                    </div>
                    <div class="form-group col-lg-1 col-md-2 col-sm-2">
                        <label for="exampleCheck1">Prefixo</label>
                        <input required type="text" name="prefixo" class="form-control" value="{{$category->prefixo}}"
                               @cannot('athlete-edit') disabled="" @endcannot>
                    </div>
                    @can('athlete-edit')
                        <button type="submit" class="btn btn-success col-lg-1 col-md-2 col-sm-2"
                                style="margin-top: 24px;">Gravar
                        </button>
                    @endcan
                </form>
                <a href="{{route('category.index')}}">
                    <button class="btn btn-danger col-lg-1 col-md-2 col-sm-2"
                            style="margin-top: 24px; margin-left: 5px;">Cancelar
                    </button>
                </a>
            </div>
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