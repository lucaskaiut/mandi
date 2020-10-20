@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <div class="form-group">
        <h1 class="title-h1">Atletas cadastrados</h1>
        @can('athlete-create')
            <a href="{{route('create.confirmed')}}">
                <button type="button" class="btn btn-custom" style="margin-left: 40%;">
                    Novo Atleta<i class="fa fa-user-plus" style="padding-left:5px;"></i>
                </button>
            </a>
        @endcan
        @if($pendingAthletes > 0)
            <a href="{{route('pending.atheltes')}}">
                <button type="button" class="btn btn-custom">
                    Cadastros Pendentes <span class="badge badge-light">{{$pendingAthletes}}</span>
                </button>
            </a>
        @endif
    </div>
@stop

@section('content')

    <div class="box-header">
    </div>
    <div class="main-box">
        <div class="box-body">
            <form action="{{route('athlete.search')}}" method="POST">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <div class="btn-group" data-toggle="buttons" style="padding-bottom: 10px;">
                    @foreach($categories as $category)
                        <label @if(isset($data['athlete_category']) && $data['athlete_category'] == $category->categoria)
                               class="btn btn-custom active"
                               @else
                               class="btn btn-custom"
                                @endif>
                            <input type="radio" name="athlete_category" id="ajaxSubmit"
                                   value="{{$category->id}}">{{$category->categoria}}
                        </label>
                    @endforeach
                    <label @if(isset($data['athlete_category']) && $data['athlete_category'] == 'Todos' or !isset($data['athlete_category']))
                           class="btn btn-custom active"
                           @else
                           class="btn btn-custom"
                            @endif>
                        <input type="radio" name="athlete_category" value="Todos">Todos
                    </label>
                </div>
            </form>
            @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{session('error')}}
                </div>
            @endif
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
                        <th scope="col">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @can('athlete-list')
                        @forelse($athletes as $athlete)
                            <tr>
                                <th scope="row">
                                    @can('athlete-edit')<a class="edit-link"
                                                           href="{{route('edit', ['id' => $athlete->id])}}">@endcan{{$athlete->matricula}}</a>
                                </th>
                                <td>{{$athlete->name}}</td>
                                <td>{{$athlete->empresa}}</td>
                                <td>{{$athlete->rg}}</td>
                                <td>{{$athlete->height}}</td>
                                @if(isset($athlete->position))
                                    <td>{{$athlete->position}}</td>
                                @else
                                    <td>#</td>
                                @endif
                                <td>{{$athlete->number_phone}}</td>
                                <td>{{$athlete->email}}</td>
                                <td class="actions">
                                        <a class="btn btn-success btn-xs"
                                           href="{{route('athlete.mensalidades', ['id' => $athlete->id])}}"><i
                                                    class="fa fa-dollar"></i> Mensalidades</a>
                                </td>
                            </tr>
                        @empty
                            <p>Não há atleta cadastrado!</p>
                        @endforelse
                    @endcan
                    </tbody>
                </table>
            </div>
            {!! $athletes->links() !!}
        </div>
    </div>
    <script type='text/javascript'>

        $(document).ready(function () {
            $('input[name=athlete_category]').change(function (e) {
                e.preventDefault();
                jQuery.ajax({
                    url: "{{ route('athlete.search') }}",
                    method: 'post',
                    data: {
                        categoria: $(this).val(), // dados que serão enviados pro controller, nesse caso enviando o valor do input #name com a variavel name
                        _token: jQuery('#token').val(),
                    },
                    dataType: 'json', // especifica que vai receber um json de retorno
                    success: function(data){
                        $("#athletes table").remove();
                        //div id funcionario receberá a nova tabela
                        $("#athletes").append("<table class='table table-hover' id='athletes' >" +
                            "<thead>" +
                            "</thead>" +
                            "<tbody>" +
                            "</tbody>" +
                            "</table>");
                        $("thead").append(
                            "<tr>" +
                            "<th scope='col'>Matrícula</th>" +
                            "<th scope='col'>Nome</th>" +
                            "<th scope='col'>Data de Nascimento</th>" +
                            "<th scope='col'>RG</th>" +
                            "<th scope='col'>Altura</th>" +
                            "<th scope='col'>Posição</th>" +
                            "<th scope='col'>Número</th>" +
                            "<th scope='col'>E-Mail</th>" +
                            "</tr>");
                        for (var i = 0; i < data.data.length; i++) {
                            var strDate = data.data[i].birth;
                            var dateParts = strDate.split("-");
                            var date = dateParts[2]+"/"+dateParts[1]+"/"+dateParts[0];
                            $("tbody").append(
                                "<tr   scope='row'>" +
                                "<td><a href='/painel/atleta/"+ data.data[i].id +"/editar' class='edit-link'>" + data.data[i].matricula + "</a></td>" +
                                "<td>" + data.data[i].name + "</td>" +
                                "<td>" + date + "</td>" +
                                "<td>" + data.data[i].rg + "</td>" +
                                "<td>" + data.data[i].height + "cm</td>" +
                                "<td>" + data.data[i].position + "</td>" +
                                "<td>" + data.data[i].number_phone + "</td>" +
                                "<td>" + data.data[i].email + "</td>" +
                                "</tr>");
                        }
                        console.log(data); // mostra o retorno do json
                        //$('#tabela').html(data); // adiciona o retorno do json no elemento de id #tabela
                    }});
            });
        });

    </script>
@stop