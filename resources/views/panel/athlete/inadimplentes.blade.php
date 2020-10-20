@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Atletas Inadimplentes</h1>
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
            <div class="box" id="athletes">
                <table class="table table-hover" id="content">
                    <thead>
                    <tr>
                        <th scope="col">Matrícula</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Unidade</th>
                        <th scope="col">RG</th>
                        <th scope="col">Altura</th>
                        <th scope="col">Posição</th>
                        <th scope="col">Número</th>
                        <th scope="col">E-Mail</th>
                        <th scope="col">Mensalidades</th>
                    </tr>
                    </thead>
                    <tbody>
                    @can('athlete-list')
                        @forelse($atletas as $atleta)
                            <tr>
                                <th scope="row">
                                    @can('athlete-edit')<a class="edit-link"
                                                           href="{{route('edit', ['id' => $atleta->id])}}">@endcan{{$atleta->matricula}}</a>
                                </th>
                                <td>{{$atleta->name}}</td>
                                <td>{{$atleta->empresa}}</td>
                                <td>{{$atleta->rg}}</td>
                                <td>{{$atleta->height}}</td>
                                @if(isset($atleta->position))
                                    <td>{{$atleta->position}}</td>
                                @else
                                    <td>#</td>
                                @endif
                                <td>{{$atleta->number_phone}}</td>
                                <td>{{$atleta->email}}</td>
                                <td class="actions">
                                    <a class="btn btn-success btn-xs"
                                       href="{{route('athlete.mensalidades', ['id' => $atleta->id])}}"><i
                                                class="fa fa-dollar"></i> Mensalidades</a>
                                </td>
                            </tr>
                        @empty
                            <p>Não há atletas inadimplentes!</p>
                        @endforelse
                    @endcan
                    </tbody>
                </table>
            </div>
            {!! $atletas->links() !!}
        </div>
    </div>
@stop