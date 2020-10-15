@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
@stop

@section('content')

    <div id="content">
        <form action="{{route('test')}}" method="POST">
            <input type="text" id="name" name="name">
            <button type="submit">Enviar</button>
        </form>
    </div>

    <script type='text/javascript'>

    </script>
@stop