@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <div class="form-group">
        <h1 class="title-h1">Categorias da Empresa {{$empresa->apelido}}</h1>
    </div>
@stop

@section('content')
    <!-- Main content -->
    <div class="main-box">

        <div class="box-body">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{session('success')}}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{session('erro')}}
                </div>
            @endif
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Categorias Não Relacionadas</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Categoria</th>
                                <th scope="col">Prefixo</th>
                                <th scope="col">Ações</th>
                            </tr>
                            </thead>

                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <th scope="row">
                                            {{$category->id}}
                                        </th>
                                        <td>{{$category->categoria}}</td>
                                        <td>{{$category->prefixo}}</td>
                                        <td class="actions">

                                                <a class="btn btn-success btn-xs"
                                                   href="{{route('category.edit', ['id' => $category->id])}}">
                                                    <i class="fa fa-edit"></i> Editar</a>

                                                <a href="{{route('company.add.category', [$empresa->id, $category->id])}}"
                                                   class="btn btn-primary btn-xs">Adicionar <i
                                                            class="fa fa-arrow-right"></i>
                                                </a>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Categorias da unidade</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Categoria</th>
                                <th scope="col">Prefixo</th>
                                <th scope="col">Ações</th>
                            </tr>
                            </thead>

                                <tbody>
                                @foreach($relacionadas as $category)
                                    <tr>
                                        <th scope="row">
                                            {{$category->id}}</a>
                                        </th>
                                        <td>{{$category->categoria}}</td>
                                        <td>{{$category->prefixo}}</td>
                                        <td class="actions">

                                                <a class="btn btn-success btn-xs"
                                                   href="{{route('category.edit', ['id' => $category->id])}}">
                                                    <i class="fa fa-edit"></i> Editar</a>

                                                <a href="{{route('company.remove.category', [$empresa->id, $category->id])}}"
                                                   class="btn btn-primary btn-xs delete"><i
                                                            class="fa fa-arrow-left"></i>
                                                    Remover
                                                </a>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop