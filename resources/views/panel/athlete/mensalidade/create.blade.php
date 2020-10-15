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
    <h1 class="title-h1">Nova Mensalidade - <b>{{$athlete->name}}</b></h1>
    @if(isset($errors) && count($errors)>0)
        <div class="alert alert-danger" style="margin-top: 10px;">
            <p>Todos os campos com * são obrigatórios</p>
        </div>
    @endif
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
@stop

@section('content')
    <div class="container-fluid">
        <div class="main-box">
            <div class="box-body">
                <form method="POST" action="{{route('store.mensalidade', ['id' => $athlete->id])}}">
                    {{csrf_field()}}
                    <h3>Dados para recibo</h3>
                    <div class="col-lg-12">
                        <div class="form-group col-form-label col-lg-2">
                            <label>Valor<b style="color: red;">*</b></label>
                            <input data-mask="#.##0,00" data-mask-reverse="true" type="text" class="form-control"
                                   name="amount" placeholder="Valor">
                        </div>
                        <div class="form-group col-lg-2">
                            <label>Referente ao Mês:</label>
                            <select class="form-control" name="ref_mes" id="exampleFormControlSelect1">
                                <option value="">Selecione</option>
                                <option value="Janeiro,1">Janeiro</option>
                                <option value="Fevereiro,2">Fevereiro</option>
                                <option value="Março,3">Março</option>
                                <option value="Abril,4">Abril</option>
                                <option value="Maio,5">Maio</option>
                                <option value="Junho,6">Junho</option>
                                <option value="Julho,7">Julho</option>
                                <option value="Agosto,8">Agosto</option>
                                <option value="Setembro,9">Setembro</option>
                                <option value="Outubro,10">Outubro</option>
                                <option value="Novembro,11">Novembro</option>
                                <option value="Dezembro,12">Dezembro</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <label>Data de pagamento<b style="color: red;">*</b></label>
                            <input type="date" class="form-control" name="pagamento">
                        </div>
                        <div class="form-group col-lg-3">
                            <label>Tipo de Pagamento</label>
                            <select class="form-control" name="pag_tipo" id="exampleFormControlSelect1">
                                <option value="">Selecione o pagamento</option>
                                @foreach($paymentMethods as $paymentMethod)
                                    <option value="{{$paymentMethod->id}},{{$paymentMethod->name}}">{{$paymentMethod->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-check col-lg-2" style="margin-top: 30px;">
                            <input class="form-check-input" name="mailSubmit" type="checkbox" value="1" id="submitMail">
                            <label class="form-check-label" for="defaultCheck1">
                                Enviar E-Mail
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">


                    </div>
                    @can('fin-create')
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary col-md-1">Confirmar</button>
                        </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>

    <div id="blanket"></div>
    <div id="aguarde">Gerando recibo</div>
    <div id="blanket"></div>
    <div id="aguardemail">Gerando recibo e enviando e-mail</div>

    <script>
        $(document).ready(function () {
            $('.btn-primary').click(function () {
                var submitMail = document.getElementById('submitMail')
                if (submitMail.checked) {
                    $('#aguardemail, #blanket').css('display', 'block');
                } else {
                    $('#aguarde, #blanket').css('display', 'block');
                }
            });
        });

        /*$(document).ready(function(){
            $('.btn-primary').click(function(){
               $('#aguarde, #blanket').css('display', 'block');
            });
        });*/
    </script>

@stop