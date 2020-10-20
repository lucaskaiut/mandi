@extends('panel.main')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <div class="form-group">
        <h1 class="title-h1">Cadastro de Clientes e Fornecedores</h1>
    </div>
@stop

@section('content')
    <div class="box-header">
    </div>
    <div class="main-box">
        <div class="box-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
            <form method="POST" action="{{route('customer.store')}}">
                {{csrf_field()}}
                <div class="col-lg-12">
                    <div class="form-group col-lg-4">
                        <label>Razão Social</label>
                        <input type="text" class="form-control"
                               name="razao_social" placeholder="Razão Social">
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Nome Fantasia</label>
                        <input type="text" class="form-control"
                               name="fantasia" placeholder="Nome Fantasia">
                    </div>
                    <div class="form-group col-lg-3" style="margin-top: 30px;">
                        <label class="radio-inline"><input type="radio" name="fornecedor" checked
                                                           value="f">Fornecedor</label>
                        <label class="radio-inline"><input type="radio" name="fornecedor" value="p">Patrocinador</label>
                        <label class="radio-inline"><input type="radio" name="fornecedor" value="c">Cliente</label>
                        <label class="radio-inline"><input type="radio" name="fornecedor" value="a">Ambos</label>
                    </div>
                    <div class="form-group col-lg-1">
                        <label for="sel1">Ativo</label>
                        <select class="form-control" name="ativo" id="sel1">
                            <option value="1" selected>Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group col-lg-2">
                        <label for="sel1">Tipo</label>
                        <select class="form-control" name="juridica" id="juridica">
                            <option value="1" selected>Jurídica</option>
                            <option value="0">Física</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-2" id="1">
                        <label>CNPJ</label>
                        <input data-mask="00.000.000/0000-00" type="text"
                               name="cnpj" class="form-control" placeholder="00.000.000/0000-00">
                    </div>
                    <div class="form-group col-lg-2" id="0" style="display: none;">
                        <label>CPF</label>
                        <input data-mask="000.000.000-00" type="text"
                               name="cpf" class="form-control" placeholder="000.000.000-00">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group col-lg-6">
                        <label>E-Mail</label>
                        <input type="email" name="email"
                               class="form-control" placeholder="E-Mail">
                    </div>
                    <div class="form-group col-lg-3">
                        <label>Telefone</label>
                        <input data-mask="(00) 0000-0000" type="text"
                               name="telefone" class="form-control" placeholder="Telefone">
                    </div>
                    <div class="form-group col-lg-3">
                        <label>Celular</label>
                        <input data-mask="(00) 00000-0000" type="text"
                               name="telefone1" class="form-control" placeholder="Celular">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group col-lg-3">
                        <label>CEP</label>
                        <input data-mask="00.000-000" type="text"
                               name="cep" class="form-control" placeholder="00.000-000">
                    </div>
                    <div class="form-group col-lg-8">
                        <label>Endereço</label>
                        <input type="text" name="endereco"
                               class="form-control" placeholder="Endereço" readonly>
                    </div>
                    <div class="form-group col-lg-1">
                        <label>Número</label>
                        <input type="text" name="numero"
                               class="form-control" placeholder="Número" maxlength="6">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group col-lg-5">
                        <label>Bairro</label>
                        <input type="text" name="bairro"
                               class="form-control" placeholder="Bairro" readonly>
                    </div>
                    <div class="form-group col-lg-5">
                        <label>Cidade</label>
                        <input type="text" name="cidade"
                               class="form-control" placeholder="Cidade" readonly>
                    </div>
                    <div class="form-group col-lg-2">
                        <label>UF</label>
                        <input type="text" name="uf" class="form-control"
                               placeholder="UF" maxlength="2" readonly>
                    </div>
                </div>
                <button type="submit" class="btn btn-custom" id="submit">Cadastrar</button>

            </form>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#submit').onClick(function(){
                if($('#juridica').val() == 1){
                    $('#0').attr('value', '');
                } else {
                    $('#1').attr('value', '');
                }
            });
        });
        $(document).ready(function () {
            $('#juridica').on('change', function () {
                var tipo = this.value;
                $('#1, #0').each(function () {
                    var usar = this.id == tipo;
                    this.required = usar;
                    this.style.display = usar ? 'block' : 'none';
                });
            });
        });
        $(document).ready(function () {
            $('input[name=cep]').blur(function(){
                var cep = jQuery('input[name=cep]').val();
                $.ajax({ // ajax
                    type: "POST",
                    url: "{{ route('buscacep') }}",
                    data: {
                        cep : cep,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function(data){
                        buscacep = JSON.parse(data.data);
                        if(buscacep['erro'] == true){
                            alert('CEP não encontrado')
                        } else {
                            $('input[name=endereco]').val(buscacep['logradouro']);
                            $('input[name=bairro]').val(buscacep['bairro']);
                            $('input[name=cidade]').val(buscacep['localidade']);
                            $('input[name=uf]').val(buscacep['uf']);
                        }
                    }
                });
            });
        });
    </script>
@stop