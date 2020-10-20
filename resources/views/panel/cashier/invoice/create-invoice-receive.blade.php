@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Lançar Conta a receber</h1>
@stop

@section('content')
    <div class="box-header">
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{session('success')}}
            </div>
        @endif
    </div>
    <div class="col-lg-12 main-box">
        <form class="form" action="{{route('store.invoice.receive')}}" method="POST">
            {{csrf_field()}}
            <div class="col-lg-12">
                <div class="form-group col-lg-2">
                    <label>Código</label>
                    <input @cannot('fin-create') readonly="" @endcannot  type="text" name="fornecedor_id"
                           class="form-control" id="codigo"
                           aria-describedby="emailHelp" placeholder="Descrição da conta">
                </div>
                <div class="col-lg-1" style="margin-top: 24px;">
                    <a href="#">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fornecedores">
                            <i class="fas fa-binoculars"></i>
                        </button>
                    </a>
                </div>
                <div class="form-group col-lg-8">
                    <label for="exampleInputEmail1">Razão Social</label>
                    <input @cannot('fin-create') readonly="" @endcannot  type="text" name="razao_social"
                           class="form-control" id="razao_social"
                           aria-describedby="emailHelp" placeholder="Descrição da conta">
                </div>
                <div class="form-group col-lg-6">
                    <label for="exampleInputEmail1">Descrição</label>
                    <input @cannot('fin-create') readonly="" @endcannot  type="text" name="descricao"
                           class="form-control" id="exampleInputEmail1"
                           aria-describedby="emailHelp" placeholder="Descrição da conta">
                </div>
                <div class="form-group col-lg-3">
                    <label for="exampleInputEmail1">Documento</label>
                    <input @cannot('fin-create') readonly="" @endcannot  type="text" name="documento"
                           class="form-control" id="exampleInputEmail1"
                           aria-describedby="emailHelp" placeholder="Documento">
                </div>
                <div class="form-group col-lg-3">
                    <label for="exampleInputPassword1">Vencimento</label>
                    <input @cannot('fin-create') readonly="" @endcannot  type="date" name="vencimento"
                           class="form-control" id="exampleInputPassword1">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group col-lg-2">
                    <label for="exampleInputEmail1">Valor</label>
                    <input @cannot('fin-create') readonly="" @endcannot  type="text" name="valor" class="form-control"
                           id="exampleInputEmail1"
                           aria-describedby="emailHelp" placeholder="Valor da conta">
                </div>
            </div>
            <div class="col-lg-12" style="margin-left: 15px;">
                @can('fin-create')
                    <button type="submit" class="btn btn-primary">Lançar</button>
                @endcan
            </div>
        </form>
    </div>
    <div class="modal fade" id="fornecedores" tabindex="-1" role="dialog" aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title title-h1" id="favoritesModalLabel">Clientes e Fornecedores</h4>
                </div>
                <div class="modal-body">
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
                                    <button class="btn btn-success btn-xs" id="select" value="{{$customer->id.'.'.$customer->razao_social}}"><i class="fas fa-check-circle"></i></button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#select').on('click', function () {
                array = $(this).val().split('.');
                codigo = array[0];
                razao_social = array[1];
                $("#codigo").val(codigo);
                $("#razao_social").val(razao_social);
                $('#fornecedores').modal('toggle');
            });
        });
    </script>
@stop