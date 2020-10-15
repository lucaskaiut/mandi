@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Cadastro de Clientes e Fornecedores</h1>
    <a href="{{route('customer.create')}}">
        <button type="button" class="btn btn-custom" style="margin-left: 40%;">
            Adicionar Cliente ou Fornecedor<i class="fa fa-user-plus" style="padding-left:5px;"></i>
        </button>
    </a>
@stop

@section('content')
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
            <form class="form-inline" method="POST" action="{{route('customer.search')}}" id="search">
                {{csrf_field()}}
                <div class="form-group">
                    <input id="id" style="width: 80px;" name="id" placeholder="Código" data-mask="00000" type="text"
                           class="form-control"
                    @if(isset($dataForm['id'])) value="{{$dataForm['id']}}" @endif>
                </div>
                <div class="form-group">
                    <label for="pwd">Razão Social</label>
                    <input id="razao_social" name="razao_social" type="text" class="form-control"
                    @if(isset($dataForm['razao_social'])) value="{{$dataForm['razao_social']}}" @endif>
                </div>
                <div class="checkbox">
                    <label><input id="fornecedor" type="radio" name="fornecedor" value="f"
                        @if(isset($dataForm['fornecedor']) && $dataForm['fornecedor'] == 'f') checked @endif> Fornecedores</label>
                </div>
                <div class="checkbox">
                    <label><input id="fornecedor" type="radio" name="fornecedor" value="p"
                        @if(isset($dataForm['fornecedor']) && $dataForm['fornecedor'] == 'p') checked @endif> Patrocinadores</label>
                </div>
                <div class="checkbox">
                    <label><input id="fornecedor" type="radio" name="fornecedor" value="c"
                                  @if(isset($dataForm['fornecedor']) && $dataForm['fornecedor'] == 'c') checked @endif> Clientes</label>
                </div>
                <div class="checkbox">
                    <label><input id="fornecedor" type="radio" name="fornecedor" value="a"
                                  @if(!isset($dataForm['fornecedor']) or (isset($dataForm['fornecedor']) && $dataForm['fornecedor'] == 'a')) checked @endif> Ambos</label>
                </div>
                <div class="form-group">
                    <label for="sel1">Ordenar</label>
                    <select name="orderBy" class="form-control">
                        <option value="id" @if(isset($dataForm['orderBy']) && $dataForm['orderBy'] == 'id') selected @endif>Código</option>
                        <option value="razao_social"
                                @if(isset($dataForm['orderBy']) && $dataForm['orderBy'] == 'razao_social') selected @endif>Nome</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Itens por página</label>
                    <input id="paginate" name="paginate" type="numeric" class="form-control" style="width: 80px;"
                    @if(isset($dataForm['paginate'])) value="{{$dataForm['paginate']}}" @endif>
                </div>
                <div class="form-group">
                    <label for="sel1">Ativo</label>
                    <select class="form-control" name="ativo" id="sel1">
                        <option value="1" @if(isset($dataForm['ativo']) && $dataForm['ativo'] == 1) selected @endif>Sim</option>
                        <option value="0" @if(isset($dataForm['ativo']) && $dataForm['ativo'] == 0) selected @endif>Não</option>
                        <option value="2" @if(isset($dataForm['ativo']) && $dataForm['ativo'] == 2) selected @endif>Ambos</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-custom">Filtrar</button>
            </form>
            <div id="customers">
                <table class="table table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col">CPF</th>
                        <th scope="col">CNPJ</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Ativo</th>
                        <th scope="col">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customers as $customer)
                        <tr @if($customer->ativo == 0) class="warning" @endif>
                            <th scope="row">{{$customer->id}}</th>
                            <td>{{$customer->razao_social}}</td>
                            <td>{{$customer->cpf}}</td>
                            <td>{{$customer->cnpj}}</td>
                            <td>{{$customer->telefone}}</td>
                            <td>@if($customer->ativo == 1) Sim @else Não @endif</td>
                            <td class="actions">
                                <a class="btn btn-success btn-xs"
                                   href="{{route('customer.edit', ['id' => $customer->id])}}"><i
                                            class="fa fa-edit"></i> Editar</a>
                                <a class="btn btn-danger btn-xs"
                                   href="{{route('customer.delete', ['id' => $customer->id    ])}}"><i
                                            class="fa fa-trash"></i> Excluir</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type='text/javascript'>


    </script>
@stop