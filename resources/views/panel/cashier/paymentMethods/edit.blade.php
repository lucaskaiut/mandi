@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Formas de Pagamento</h1>
@stop

@section('content')
    <div class="main-box">
        <div class="col-lg-12">
            <form method="POST" action="{{route('payment.method.update', ['id' => $paymentMethodToEdit->id])}}">
                {{csrf_field()}}

                <div class="form-group col-lg-5">
                    <label for="exampleInputEmail1">Forma de pagamento</label>
                    <input type="text" name="name" class="form-control" value="{{$paymentMethodToEdit->name}}"
                           @cannot('fin-edit') readonly="" @endcannot>
                </div>
                <div class="form-group col-lg-2">
                    <label for="sel1">Categoria</label>
                    <select class="form-control">
                        <option value="dinheiro">Dinheiro</option>
                        <option value="cartao" @if($paymentMethodToEdit->categoria == 'cartao') selected @endif>Cartão</option>
                        <option value="bancaria" @if($paymentMethodToEdit->categoria == 'bancaria') selected @endif>Bancária</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success col-lg-2" style="margin-top: 24px;">Gravar</button>
            </form>
            <a href="{{route('payment.method.index')}}">
                <button class="btn btn-danger col-lg-2" style="margin-top: 24px; margin-left: 5px;">Cancelar</button>
            </a>
        </div>
        <div class="box-body">
            <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Forma de Pagamento</th>
                    <th>Categoria</th>
                    <th>Ações</th>
                </tr>
                </thead>
                @can('fin-list')
                    <tbody>
                    @forelse($paymentMethods as $paymentMethod)
                        <tr>
                            <td>{{$paymentMethod->id}}</td>
                            <td>{{$paymentMethod->name}}</td>
                            <td>{{$paymentMethod->categoria}}</td>
                            <td class="actions">
                                @can('fin-edit')
                                    <a class="btn btn-success btn-xs"
                                       href="{{route('payment.method.edit', ['id' => $paymentMethod->id])}}"><i
                                                class="fa fa-edit"></i> Editar</a>
                                @endcan
                                @can('fin-delete')
                                    <a class="btn btn-danger btn-xs"
                                       href="{{route('payment.method.delete', ['id' => $paymentMethod->id])}}"
                                       data-toggle="modal" data-target="#delete-modal"><i
                                                class="fa fa-trash delete"></i> Excluir</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <p>Não há forma de pagamento cadastrada</p>
                    @endforelse
                    </tbody>
                @endcan
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $(".delete").click(function () {
                if (!confirm("Tem certeza que deseja apagar essa forma de pagamento?")) {
                    return false;
                }
            });
        });
    </script>
@stop