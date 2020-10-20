@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <div class="form-group">
        <h1 class="title-h1">Empresas</h1>

            <a href="{{route('company.create')}}">
                <button type="button" class="btn btn-custom" style="margin-left: 45%;">
                    Nova Empresa<i class="fa fa-plus-circle" style="padding-left:5px;"></i>
                </button>
            </a>

    </div>
@stop

@section('content')
    <div class="box-header">
    </div>
    <div class="main-box">
        <div class="box-body">
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
            @if(count($companies) == 0)
                <div class="alert alert-danger">
                    <p>Não há dados para exibição</p>
                </div>
            @endif
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Apelido</th>
                    <th scope="col">CPF/CNPJ</th>
                    <th scope="col">E-Mail</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">Celular</th>
                    <th scope="col">Data de cadastro</th>
                    <th scope="col">Ações</th>
                </tr>
                </thead>

                    <tbody>
                    @foreach($companies as $company)
                        <tr>
                            <th scope="row">
                                {{$company->id}}</a>
                            </th>
                            <td>{{$company->nome}}</td>
                            <td>{{$company->apelido}}</td>
                            <td>@if(isset($company->cpf)) {{$company->cpf}} @else {{$company->cnpj}} @endif</td>
                            <td>{{$company->email}}</td>
                            <td>{{ $company->telefone}}</td>
                            <td>{{ $company->celular}}</td>
                            <td>{{ date( 'd/m/Y' , strtotime($company->created_at))}}</td>
                            <td class="actions">

                                    <a class="btn btn-success btn-xs"
                                       href="{{route('company.edit', ['id' => $company->id])}}">
                                        <i class="fa fa-edit"></i> Editar</a>

                                    <a href="{{route('company.categorias', ['id' => $company->id])}}"
                                       class="btn btn-primary btn-xs"><i class="fa fa-layer-group"></i>
                                        Categorias</a>

                                    <a class="btn btn-danger btn-xs"
                                       href="{{route('company.delete', ['id' => $company->id])}}">
                                        <i class="fa fa-trash-alt delete"></i> Excluir</a>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>

            </table>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $(".delete").click(function () {
                if (!confirm("Tem certeza que deseja apagar essa empresa?")) {
                    return false;
                }
            });
        });
    </script>
@stop