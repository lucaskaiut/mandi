@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Pagamento de Conta</h1>
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
        <form class="form form-submit" action="{{route('payment.store.invoice.pay')}}" method="POST">
            {{csrf_field()}}
            <input type="hidden" name="id" value="{{$invoice->id}}">
            <input type="hidden" name="descricao" value="{{$invoice->descricao}}">
            <input type="hidden" name="vencimento" value="{{$invoice->vencimento}}">
            <input type="hidden" name="valor_pendente" value="{{$invoice->valor_pendente}}">
            <div class="col-lg-12">
                <div class="form-group col-lg-8">
                    <label for="exampleInputEmail1">Descrição</label>
                    <input type="text" name="descricao" value="{{$invoice->descricao}}" class="form-control"
                           id="exampleInputEmail1"
                           aria-describedby="emailHelp" placeholder="Descrição da conta" readonly>
                </div>
                <div class="form-group col-lg-4">
                    <label for="exampleInputPassword1">Vencimento</label>
                    <input type="date" name="vencimento" value="{{$invoice->vencimento}}"
                           class="form-control" id="exampleInputPassword1" readonly>
                </div>
                <div class="form-group col-lg-2">
                    <label for="exampleInputEmail1">Valor Original</label>
                    <input type="text" name="valor_original"
                           value="R${{number_format($invoice->valor_original, 2, ',', '.')}}" class="form-control"
                           id="valor_original"
                           aria-describedby="emailHelp" placeholder="Valor da conta" readonly>
                </div>
                <div class="form-group col-lg-2">
                    <label for="exampleInputEmail1">Valor Pendente</label>
                    <input type="text" name="valor_pendente"
                           value="R${{number_format($invoice->valor_pendente, 2, ',', '.')}}" class="form-control"
                           id="valor_pendente"
                           aria-describedby="emailHelp" placeholder="Valor da conta" readonly>
                </div>
                <div class="form-group col-lg-2">
                    <label for="exampleInputEmail1">Valor Pago</label>
                    <input type="text" name="valor_pago" class="form-control" id="valor_pago" data-mask="#.##0,00"
                           data-mask-reverse="true">
                </div>
                <div class="form-group col-lg-6" style="margin-top:30px;">
                    <p id="troco" style="display: none;">Troco em dinheiro no valor de R$200,00</p>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group col-lg-3">
                    <label for="exampleFormControlSelect1">Forma de Pagamento</label>
                    <select class="form-control" name="pag_tipo" id="formapagamento">
                        <option value="" selected>Selecione a forma de pagamento</option>
                        @foreach($paymentMethods as $paymentMethod)
                            <option value="{{$paymentMethod->id}},{{$paymentMethod->name}},{{$paymentMethod->categoria}}">{{$paymentMethod->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-7">
                    <label for="exampleInputEmail1">Referência</label>
                    <input type="text" name="referencia" class="form-control" id="exampleInputEmail1"
                           aria-describedby="emailHelp" placeholder="Referência">
                </div>
                <div class="form-group col-lg-2">
                    <label for="exampleInputEmail1">Documento</label>
                    <input type="text" name="documento" class="form-control" id="exampleInputEmail1"
                           aria-describedby="emailHelp" value="{{$invoice->documento    }}">
                </div>
            </div>
            @can('fin-edit')
                <div class="col-lg-12" style="margin-left: 15px;">
                    <button type="submit" class="btn btn-primary" id="btn-toggle">Pagar</button>
                </div>
            @endcan
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#valor_pago').blur(function () {
                valor_pendente = $('#valor_pendente').val().replace('R$', '');
                valor_pendente = valor_pendente.replace(',', 'v');
                valor_pendente = valor_pendente.replace('.', 'p');
                valor_pendente = valor_pendente.replace('p', '');
                valor_pendente = valor_pendente.replace('v', '.');
                valor_pago = $('#valor_pago').val().replace(',', 'v');
                valor_pago = valor_pago.replace('.', 'p');
                valor_pago = valor_pago.replace('p', '');
                valor_pago = valor_pago.replace('v', '.');
                diferenca = valor_pendente - valor_pago;
                diferenca = diferenca.toFixed(2);
                dfrOriginal = diferenca;
                if (diferenca < 0) {
                    diferenca = diferenca.replace('-', '');
                    diferenca = diferenca.replace('.', ',');
                    $('#troco').css('display', '');
                    $('#troco').css('color', 'green');
                    $('#troco').css('font-weight', 'bold');
                    $('#troco').text("Troco em dinheiro: R$" + diferenca);
                    return diferenca;
                } else {
                    if (diferenca > 0) {
                        diferenca = diferenca.replace('.', ',');
                        $('#troco').css('display', '');
                        $('#troco').css('color', 'red');
                        $('#troco').css('font-weight', 'bold');
                        $('#troco').html("Diferença: R$" + diferenca + "<small> (Ao lançar um valor inferior ao valor pendente, será considerado uma baixa parcial)</small>");
                        $('#btn-toggle').prop('readonly', false);
                        return diferenca;
                    }
                }
            });
            $('#formapagamento').change(function (e) {
                array = $(this).val().split(',');
                e.preventDefault();
                jQuery.ajax({
                    url: "{{ route('invoice.getformapagamento') }}",
                    method: 'post',
                    data: {
                        formapagamento: array, // dados que serão enviados pro controller, nesse caso enviando o valor do input #name com a variavel name
                        _token: "{{csrf_token()}}",
                    },
                    dataType: 'json', // especifica que vai receber um json de retorno
                    success: function (data) {
                        if (data.data['categoria'] == 'cartao') {
                            if (dfrOriginal < 0) {
                                $('#btn-toggle').attr('readonly', 'readonly');
                                $('#troco').css('display', '');
                                $('#troco').css('color', 'red');
                                $('#troco').css('font-weight', 'bold');
                                $('#troco').text("Não é possível lançar valor maior para pagamento em cartão");
                            }
                            $('#btn-toggle').click(function () {
                                console.log('cartao');
                                $('.form-submit').attr('action', '{{route('create.cartao.movimento.saida')}}');
                            });
                        } else {
                            $('#btn-toggle').prop('readonly', false);
                            if (dfrOriginal < 0) {
                                $('#troco').css('display', '');
                                $('#troco').css('color', 'green');
                                $('#troco').css('font-weight', 'bold');
                                $('#troco').text("Troco em dinheiro: R$" + diferenca);
                            } else {
                                $('#troco').css('display', '');
                                $('#troco').css('color', 'red');
                                $('#troco').css('font-weight', 'bold');
                                $('#troco').html("Diferença: R$" + diferenca + "<small> (Ao lançar um valor inferior ao valor pendente, será considerado uma baixa parcial)</small>");
                            }
                            $('#btn-toggle').click(function () {
                                $('.form-submit').attr('action', '{{route('payment.store.invoice.pay')}}');
                            });
                        }
                    }

                });
            });


        });
    </script>
@stop