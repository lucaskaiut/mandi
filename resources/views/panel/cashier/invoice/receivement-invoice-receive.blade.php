@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Baixar Conta</h1>
@stop

@section('content')
    <div class="box-header">
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{session('success')}}
            </div>
        @endif
    </div>
    <div class="col-md-12 main-box">
        <form class="form form-submit" action="{{route('receivement.store.invoice.receive')}}" method="POST">
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
                    <select id="formapagamento" class="form-control" name="pag_tipo">
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
                    <label id="documento">Documento</label>
                    <input type="text" name="documento" class="form-control documento" id="exampleInputEmail1"
                           aria-describedby="emailHelp" value="{{$invoice->documento}}">
                </div>
            </div>
            @can('fin-edit')
                <div class="col-lg-12" style="margin-left: 15px;">
                    <button id="btn-toggle" type="submit" class="btn btn-primary">Receber</button>
                </div>
            @endcan
        </form>
    </div>
    <div class="modal fade" id="modalCartao" tabindex="-1" role="dialog" aria-labelledby="modalCartao"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title title-h1" id="exampleModalLabel">Desdobramento de cartão</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <table class="table table-bordered table-hover" style="margin-bottom: 5px;">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Bandeira</th>
                                <th>Taxa</th>
                                <th>Dias</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bandeiras as $bandeira)
                                <tr>
                                    <td scope="row"><input type="radio" name="id_bandeira" value="{{$bandeira->id}}">
                                    </td>
                                    <td scope="row">{{$bandeira->nome}}</td>
                                    <td scope="row">{{$bandeira->taxa}}</td>
                                    <td scope="row">{{$bandeira->dias}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="col-lg-12">
                            <div class="form-group col-lg-5">
                                <label>NºCV</label>
                                <input type="text" class="form-control" name="cv">
                            </div>
                            <div class="form-group col-lg-3">
                                <label>Valor</label>
                                <input type="text" class="form-control valor-digitado-modal" name="valor"
                                       data-mask="#.##0,00"
                                       data-mask-reverse="true" maxlength="14" id="valor">
                            </div>
                            <div class="form-group col-lg-2">
                                <label>Parcelas</label>
                                <input type="number" class="form-control" name="NParcelas">
                            </div>
                            <div class="col-lg-1">
                                <button class="btn btn-success btn-add" style="margin-top: 24px;"><i
                                            class="fas fa-plus-circle"></i></button>
                            </div>
                        </div>
                        <p class="valor-lancado-modal"></p>
                        <div id="valores-modal">
                        </div>
                        <p class="diferenca"></p>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary">Close</button>
                            <button type="button" class="btn btn-primary save">Save changes</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
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
                    } else {
                        $('#troco').css('display', 'none');
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
                            $('.form-submit').attr('action', '{{route('create.cartao.movimento')}}');
                            if (dfrOriginal < 0) {
                                $('#troco').css('display', '');
                                $('#troco').css('color', 'red');
                                $('#troco').css('font-weight', 'bold');
                                $('#troco').text("Não é possível lançar valor maior para recebimento em cartão");
                                $('#btn-toggle').attr('readonly', 'readonly');
                            }
                            $('#btn-toggle').click(function () {
                            });
                        } else {
                            $('#btn-toggle').prop('readonly', false);
                            $('.form-submit').attr('action', '{{route('receivement.store.invoice.receive')}}')
                            if (dfrOriginal < 0) {
                                $('#troco').css('display', '');
                                $('#troco').css('color', 'green');
                                $('#troco').css('font-weight', 'bold');
                                $('#troco').text("Troco em dinheiro: R$" + diferenca);
                            } else {
                                if (dfrOriginal > 0) {
                                    $('#troco').css('display', '');
                                    $('#troco').css('color', 'red');
                                    $('#troco').css('font-weight', 'bold');
                                    $('#troco').html("Diferença: R$" + diferenca + "<small> (Ao lançar um valor inferior ao valor pendente, será considerado uma baixa parcial)</small>");
                                } else {
                                    $('#troco').css('display', 'none');
                                }
                            }
                            $('#btn-toggle').click(function () {
                            });
                        }
                    }
                });
            });
        });
    </script>
@stop