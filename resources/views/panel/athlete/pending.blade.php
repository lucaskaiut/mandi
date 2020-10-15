@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <div class="form-group">
        <h1>Cadastros Pendentes</h1>
        <a href="/painel/atleta/cadastrar">
            <button type="button" class="btn btn-custom">
                Novo Atleta<i class="fa fa-user-plus" style="padding-left:5px;"></i>
            </button>
        </a>
    </div>
@stop

@section('content')
    <div class="box-header">
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{session('success')}}
            </div>
        @endif
    </div>
    <div class="box">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Data de Nascimento</th>
                <th scope="col">RG</th>
                <th scope="col">Altura</th>
                <th scope="col">Posição</th>
                <th scope="col">Número</th>
                <th scope="col">E-Mail</th>
            </tr>
            </thead>
            <tbody>
            @forelse($athletes as $athlete)
                <tr>
                    <th scope="row">
                        <a class="edit-link" href="/painel/atleta/{{$athlete->id}}/editar">{{$athlete->id}}</a>
                    </th>
                    <td>{{$athlete->name}}</td>
                    <td>{{ date( 'd/m/Y' , strtotime($athlete->birth))}}</td>
                    <td>{{$athlete->rg}}</td>
                    <td>{{$athlete->height}}cm</td>
                    @if(isset($athlete->position))
                        <td>{{$athlete->position}}</td>
                    @else
                        <td>#</td>
                    @endif
                    <td>{{$athlete->number_phone}}</td>
                    <td>{{$athlete->email}}</td>
                </tr>
            @empty
                <p>Não há atleta cadastrado!</p>
            @endforelse
            </tbody>
        </table>
        {!! $athletes->links() !!}
    </div>
    <script type='text/javascript'>

        $(document).ready(function() {
            $('input[name=athlete_category]').change(function(){
                $('form').submit();
            });
        });

    </script>
@stop