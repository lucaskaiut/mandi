@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')
<style>
    #blanket, #aguarde, #aguardemail {
        position: fixed;
        display: none;
    }

    #blanket {
        left: 0;
        top: 0;
        background-color: #f0f0f0;
        filter: alpha(opacity=65);
        height: 100%;
        width: 100%;
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=65)";
        opacity: 0.65;
        z-index: 9998;
    }

    #aguarde, #aguardemail {
        width: auto;
        height: 30px;
        top: 40%;
        left: 45%;
        background: url('http://i.imgur.com/SpJvla7.gif') no-repeat 0 50%;
        font-weight: bold;
        font-family: Arial, Helvetica, sans-serif;
        z-index: 9999;
        padding-left: 27px;
    }
</style>
@section('content_header')
    <div class="form-group">
        <h1 class="title-h1">Mensalidades de {{$athlete->name}}</h1>
        @can('fin-create')
            <div style="margin-left: 40%;">
                <a href="{{route('create.mensalidade', ['id' => $athlete->id])}}">
                    <button type="button" class="btn btn-info">Nova Mensalidade</button>
                </a>
            </div>
        @endcan
    </div>
@stop

@section('content')
    <div class="container-fluid">
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
                @can('fin-list')
                    <div class="col-lg-12" style="padding: 5px;">
                        <form class="" action="{{route('search.mensalidade.athlete', ['id' => $athlete->id])}}"
                              method="POST">
                            {{csrf_field()}}
                            <div class="form-group col-lg-1 col-md-6 col-form-label" style="padding-left: 0;">
                                <label>Recibo</label>
                                <input data-mask="00000" type="text" class="form-control" name="id" placeholder="Nº"
                                       @if(isset($dataForm['id'])) value="{{$dataForm['id']}} @endif">
                            </div>
                            <div class="form-group col-lg-2">
                                <label>Itens por página</label>
                                <input data-mask="00" type="numeric" name="paginate" class="form-control"
                                       style="width: 50px;" id="inputPassword"
                                       @if(isset($dataForm['paginate'])) value="{{$dataForm['paginate']}} @endif">
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="exampleFormControlSelect1">Ordenar</label>
                                <select name="orderBy" class="form-control" id="exampleFormControlSelect1">
                                    <option value="id"
                                            @if(isset($dataForm) && $dataForm['orderBy'] == 'id') selected @endif>
                                        Recibo
                                    </option>
                                    <option value="atleta"
                                            @if(isset($dataForm) && $dataForm['orderBy'] == 'atleta') selected @endif>
                                        Atleta
                                    </option>
                                    <option value="ref_mes"
                                            @if(isset($dataForm) && $dataForm['orderBy'] == 'ref_mes') selected @endif>
                                        Mês
                                    </option>
                                    <option value="created_at"
                                            @if(isset($dataForm) && $dataForm['orderBy'] == 'created_at') selected @endif>
                                        Lançamento
                                    </option>
                                    <option value="pagamento"
                                            @if(isset($dataForm) && $dataForm['orderBy'] == 'pagamento') selected @endif>
                                        Pagamento
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-lg-4" style="margin-top: 25px;">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                            <div class="form-group col-lg-3 col-md-4">
                                <label>Lançamento Inicio</label>
                                <input type="date" class="form-control" name="lancamento_inicio"
                                       @if(isset($dataForm['lancamento_inicio'])) value="{{$dataForm['lancamento_inicio']}}" @endif>
                            </div>
                            <div class="form-group col-lg-3 col-md-4">
                                <label>Lançamento Fim</label>
                                <input type="date" class="form-control" name="lancamento_fim"
                                       @if(isset($dataForm['lancamento_fim'])) value="{{$dataForm['lancamento_fim']}}" @endif>
                            </div>
                            <div class="form-group col-lg-3 col-md-4" style="padding-left: 0;">
                                <label>Pagamento Inicio</label>
                                <input type="date" class="form-control" name="pagamento_inicio"
                                       @if(isset($dataForm['pagamento_inicio'])) value="{{$dataForm['pagamento_inicio']}}" @endif>
                            </div>
                            <div class="form-group col-lg-3 col-md-4">
                                <label>Pagamento Fim</label>
                                <input type="date" class="form-control" name="pagamento_fim"
                                       @if(isset($dataForm['pagamento_fim'])) value="{{$dataForm['pagamento_fim']}}" @endif>
                            </div>
                        </form>
                    </div>
                @endcan
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Recibo</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Atleta</th>
                        <th scope="col">Correspondente a</th>
                        <th scope="col">Lançamento</th>
                        <th scope="col">Pagamento</th>
                        <th scope="col">RG</th>
                        <th scope="col">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @can('fin-list')
                        @forelse($mensalidades as $mensalidade)
                            <tr>
                                <th scope="row">
                                    {{$mensalidade->recibo}}</a>
                                </th>
                                <td>R${{ number_format($mensalidade->amount, 2, ',', '.') }}</td>
                                <td>{{$mensalidade->atleta}}</td>
                                <td>{{$mensalidade->ref_mes}}</td>
                                <td>{{ date( 'd/m/Y' , strtotime($mensalidade->created_at))}}</td>
                                <td>{{ date( 'd/m/Y' , strtotime($mensalidade->pagamento))}}</td>
                                <td>{{$mensalidade->rg}}</td>
                                <td>
                                    <a class="btn btn-info btn-xs"
                                       href="{{route('download.mensalidade', ['id' => $mensalidade->recibo])}}"><i
                                                class="fa fa-file-download"></i> Baixar Recibo</a>
                                    <a class="btn btn-info btn-xs" data-toggle="modal" data-target="#sendMail"><i
                                                class="fa fa-envelope"></i> Enviar</a>
                            </tr>
                        @empty
                            <p>Ainda não há nenhum recibo!</p>
                        @endforelse
                    @endcan
                    </tbody>
                </table>
                @if(isset($dataForm))
                    {!! $mensalidades->appends($dataForm)->links() !!}
                @else
                    {!! $mensalidades->links() !!}
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="sendMail" tabindex="-1" role="dialog" aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title title-h1" id="favoritesModalLabel">@if (isset($mensalidade))Enviar Recibo {{$mensalidade->recibo}} por email @endif</h4>
                </div>
                <div class="modal-body">
                    @can('fin-edit')
                        <form @if(isset($mensalidade)) action="{{route('mail.mensalidade', ['id' => $mensalidade->recibo])}}" @endif method="POST">
                            {{csrf_field()}}
                            <div class="form-group">
                                <label style="text-align: left;" for="exampleFormControlInput1">E-Mail</label>
                                <small>(Caso deixe o email em branco o recibo será enviado para o e-mail disponível no cadastro do atleta.)</small>
                                <input type="email" name="email" class="form-control" id="exampleFormControlInput1"
                                       placeholder="E-Mail">
                            </div>
                            <span class="pull-right">
                            <button type="submit" class="btn btn-primary send-mail">Enviar</button>
                        </span>
                        </form>
                        <button type="button" class="btn btn-custom" data-dismiss="modal" style="margin-right: 5px;">
                            Cancelar
                        </button>
                    @endcan
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <div id="blanket"></div>
    <div id="aguardemail">Enviando e-mail</div>

    <script type='text/javascript'>

        $(document).ready(function () {
            $('input[name=athlete_category]').change(function () {
                $('form').submit();
            });
        });

        $(document).ready(function () {
            $('.send-mail').click(function () {
                $('#aguardemail, #blanket').css('display', 'block');
            });
        });


    </script>
@stop