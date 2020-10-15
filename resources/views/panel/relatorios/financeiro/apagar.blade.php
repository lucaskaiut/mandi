@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Relatório de Contas a Pagar</h1>
@stop

@section('content')
    <div class="main-box">
        <div class="box-body">
            <div class="col-lg-12">
                <form target="_blank" class="form-group" action="{{route('relatorios.apagar.list')}}" method="POST">
                    {{csrf_field()}}
                    <div class="form-group col-lg-3 col-form-label">
                        <label>Lançamento Inicial</label>
                        <input @cannot('fin-list') readonly="" @endcannot  type="date" class="form-control"
                               name="lancamento_inicio">
                    </div>
                    <div class="form-group col-lg-3 col-form-label">
                        <label>Lançamento Final</label>
                        <input @cannot('fin-list') readonly="" @endcannot  type="date" class="form-control"
                               name="lancamento_fim">
                    </div>
                    <div class="form-group col-lg-3 col-form-label">
                        <label>Vencimento Inicial</label>
                        <input @cannot('fin-list') readonly="" @endcannot  type="date" class="form-control"
                               name="vencimento_inicio">
                    </div>
                    <div class="form-group col-lg-3 col-form-label">
                        <label>Vencimento Final</label>
                        <input @cannot('fin-list') readonly="" @endcannot  type="date" class="form-control"
                               name="vencimento_fim">
                    </div>
                    <div class="form-group col-lg-3">
                        <label>Ordenar</label>
                        <select class="form-control" name="orderBy" id="exampleFormControlSelect1">
                            <option value="id">Sequência</option>
                            <option value="descricao">Descrição</option>
                            <option value="valor">Valor</option>
                            <option value="vencimento">Vencimento</option>
                            <option value="created_at">Lançamento</option>
                        </select>
                    </div>
                    <div class="col-lg-12">
                        @can('fin-list')
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop