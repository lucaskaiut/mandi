@extends('adminlte::page')

@section('title', 'Dynamo Voleibol')

@section('content_header')
    <h1 class="title-h1">Cadastro de Atleta</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@stop

@section('content')
    <div class="container-fluid">
        <div class="main-box">
            <div class="box-body">
                <form method="POST" action="{{route('store.athlete.confirmed')}}" id="formularioAtleta">
                    <input type="hidden" name="matricula">
                    <input type="hidden" name="id" value="0">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <div class="col-lg-12">
                        <h3>Dados do Atleta</h3>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group col-lg-8 col-form-label">
                            <label>Nome do atleta<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="name" placeholder="Nome"
                                   value="{{old('name')}}">
                        </div>
                        <div class="form-group col-lg-2">
                            <label>Posição</label>
                            <input type="text" class="form-control" name="position" placeholder="Posição">
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="exampleFormControlSelect1">Sexo</label>
                            <select class="form-control" name="gender" id="exampleFormControlSelect1">
                                <option value="m">Masculino</option>
                                <option value="f">Feminino</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group col-lg-3 col-md-4">
                            <label>Unidade<b style="color: red;">*</b></label>
                            <select class="form-control" name="company_id" id="company">
                                @foreach($empresas as $empresa)
                                    <option value="{{$empresa->id}}">{{$empresa->apelido}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <label>Categoria</label>
                            <select class="form-control" name="athlete_category" id="category">
                                <option value="" selected>Selecione a categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->categoria}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group col-lg-3 col-md-4">
                            <label>Data de nascimento<b style="color: red;">*</b></label>
                            <input type="date" class="form-control" name="birth" placeholder="Data de nascimento">
                        </div>
                        <div class="form-group col-lg-4 col-md-5">
                            <label>E-Mail</label>
                            <input type="email" class="form-control" name="email" placeholder="E-Mail">
                        </div>
                        <div class="form-group col-lg-3 col-md-6">
                            <label>Telefone<b style="color: red;">*</b></label>
                            <input data-mask="(00) 00000-0000" type="text" class="form-control" name="number_phone"
                                   placeholder="Telefone/Celular"
                                   maxlength="11">
                        </div>
                        <div class="form-group col-lg-2 col-md-6">
                            <label>RG<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="rg" placeholder="RG" maxlength="11">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group col-lg-3 col-md-5">
                            <label>CEP<b style="color: red;">*</b></label>
                            <input data-mask="00000-000" type="text" class="form-control" name="cep" id="cep" placeholder="00000-000">
                        </div>
                        <div class="form-group col-lg-6 col-md-10">
                            <label>Rua<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="address" placeholder="Rua" readonly>
                        </div>
                        <div class="form-group col-lg-2 col-md-2">
                            <label>Número<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="number" placeholder="Número" maxlength="5">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group col-lg-4 col-md-6">
                            <label>Bairro<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="neighborhood" placeholder="Bairro" readonly>
                        </div>
                        <div class="form-group col-lg-6 col-md-4">
                            <label>Cidade<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="city" placeholder="Cidade" readonly>
                        </div>
                        <div class="form-group col-lg-2 col-md-2">
                            <label>Estado<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="uf" placeholder="UF" maxlength="2" readonly>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <h3>Dados do responsável
                            <small>(caso atleta seja menor de idade)</small>
                        </h3>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group col-lg-6 col-md-6">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="parents_name"
                                   placeholder="Nome do responsável">
                        </div>
                        <div class="form-group col-lg-3 col-md-3">
                            <label>Telefone</label>
                            <input data-mask="(00) 0000-0000" type="text" class="form-control"
                                   name="parents_number_phone"
                                   placeholder="Número do responsável">
                        </div>
                        <div class="form-group col-lg-3 col-md-3">
                            <label>RG</label>
                            <input type="text" class="form-control" name="parents_rg" placeholder="RG do responsável">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <h3>Dados da Anamnese do Atleta</h3>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group col-lg-2 col-md-2">
                            <label>Altura<b style="color: red;">*</b></label>
                            <input data-mask="0,00" type="text" class="form-control" name="height" placeholder="Altura"
                                   maxlength="3">
                        </div>
                        <div class="form-group col-lg-2 col-md-2">
                            <label>Peso<b style="color: red;">*</b></label>
                            <input data-mask="#00,00" data-mask-reverse="true" type="text" class="form-control" name="weight" placeholder="Peso"
                                   maxlength="6">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="col-lg-3 col-md-7">Possui alguma restrição na prática de atividade física?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="phys_restriction" value="1"> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="phys_restriction" value="0"> Não
                            </label>
                        </div>
                        <div class="form-group col-lg-4">
                            <input type="text" class="form-control" name="phys_restriction_name"
                                   placeholder="Qual(is)?">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="col-lg-3 col-md-7">Atualmente sente dor em alguma região do corpo?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="body_pain" value="1"> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="body_pain" value="0"> Não
                            </label>
                        </div>
                        <div class="form-group col-lg-4">
                            <input type="text" class="form-control" name="body_pain_location" placeholder="Onde?">
                        </div>
                    </div>
                    <div class="form-group col-lg-12 col-md-12">
                        <label class="col-lg-3 col-md-7">Já perdeu a consciência como resultado de desmaio?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="faint" value="1"> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="faint" value="0"> Não
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="col-lg-3 col-md-5">Possui algum tipo de desvio postural?</label>
                        <div class="form-group col-lg-4 col-md-4">
                            <select name="posture_deviation_name" class="form-control">
                                <option value="Não">Não</option>
                                <option value="Lordose Cervical">Lordose Cervical</option>
                                <option value="Lordose Lombar">Lordose Lombar</option>
                                <option value="Cifose Dorsal">Cifose Dorsal</option>
                                <option value="Escoliose">Escoliose</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="col-lg-3 col-md-7">Possui alguma lesão óssea, lesão articular ou muscular?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="bone_injury" value="1"> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="bone_injury" value="0"> Não
                            </label>
                        </div>
                        <div class="form-group col-lg-4">
                            <input type="text" class="form-control" name="bone_injury_name" placeholder="Local:">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="col-lg-3 col-md-6">Foi submetido a algum tipo de cirurgia?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="surgery" value="1"> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="surgery" value="0"> Não
                            </label>
                        </div>
                        <div class="form-group col-lg-4">
                            <input type="text" class="form-control" name="surgery_name" placeholder="Qual(is)?">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="col-lg-3 col-md-6">Possui alguma deficiência ou limitação física?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="physical_disability" value="1"> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="physical_disability" value="0"> Não
                            </label>
                        </div>
                        <div class="form-group col-lg-4">
                            <input type="text" class="form-control" name="physical_disability_name"
                                   placeholder="Qual(is)?">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="col-lg-3 col-md-6">Pratica algum tipo de atividade física?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="exercise" value="1"> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="exercise" value="0"> Não
                            </label>
                        </div>
                        <div class="form-group col-lg-4">
                            <input type="text" class="form-control" name="exercise_name" placeholder="Qual(is)?">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="col-lg-3 col-md-6">Como você considera sua alimentação?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-3 col-md-6">
                            <label class="radio-inline">
                                <input type="radio" name="feeding" value="Adequada"> Adequada
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="feeding" value="Inadequada"> Inadequada
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="feeding" value="Regular"> Regular
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="col-lg-3 col-md-8">Tem histórico de Alcoolísmo, Tabagismo ou Sedentarismo?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="addiction" value="1"> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="addiction" value="0"> Não
                            </label>
                        </div>
                        <div class="form-group col-lg-4">
                            <input type="text" class="form-control" name="addiction_name" placeholder="Qual(is)?">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="col-lg-3 col-md-6">Tem alguma doença de tratamento contínuo?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="disease" value="1"> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="disease" value="0"> Não
                            </label>
                        </div>
                        <div class="form-group col-lg-4">
                            <input type="text" class="form-control" name="disease_name" placeholder="Qual(is)?">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="col-lg-3 col-md-9">Tem histórico familiar de doenças coronarianas e/ou
                            hipertensão?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-3">
                            <label class="radio-inline">
                                <input type="radio" name="family_disease" value="1"> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="family_disease" value="0"> Não
                            </label>
                        </div>
                        <div class="form-group col-lg-4">
                            <input type="text" class="form-control" name="family_who_obs" placeholder="Descrição: ">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="col-lg-3 col-md-6">Faz uso de medicamentos contínuos?<b style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="drug" value="1"> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="drug" value="0"> Não
                            </label>
                        </div>
                        <div class="form-group col-lg-4">
                            <input type="text" class="form-control" name="drug_name" placeholder="Qual(is)?">
                        </div>
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="col-lg-3 col-md-6">Gravidez atual ou nos últimos 3 meses?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="recent_pregnancy" value="1"> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="recent_pregnancy" value="0"> Não
                            </label>
                        </div>
                        <div class="form-group col-lg-4">
                            <input type="text" class="form-control" name="pregnancy_number"
                                   placeholder="Nº de gestações:">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group col-lg-8">
                            <label for="exampleFormControlTextarea2">Informações complementares</label>
                            <textarea class="form-control rounded-0" name="obs" id="exampleFormControlTextarea2"
                                      rows="3"></textarea>
                        </div>
                    </div>
                    @can('athlete-create')
                    <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                        @endcan
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#company').change(function(){
                var company = jQuery('#company').val();
                $.ajax({ // ajax
                    type: "POST",
                    url: "{{ route('company.athlete.create') }}",
                    data: {
                        company : company,
                        _token: jQuery('#token').val(),
                    },
                    success: function(data){
                        $("#category option").remove();
                        for (var i = 0; i < data.data.length; i++) {
                            $('#category').append('<option value="' + data.data[i].id + '">' + data.data[i].categoria + "</option>");
                        }
                    }
                });
            });
        });

        $(document).ready(function () {
            $('#cep').blur(function(){
                var cep = jQuery('#cep').val();
                $.ajax({ // ajax
                    type: "POST",
                    url: "{{ route('buscacep') }}",
                    data: {
                        cep : cep,
                        _token: jQuery('#token').val(),
                    },
                    dataType: 'json',
                    success: function(data){
                        buscacep = JSON.parse(data.data);
                        if(buscacep['erro'] == true){
                            alert('CEP não encontrado');
                        } else {
                            $('input[name=address]').val(buscacep['logradouro']);
                            $('input[name=neighborhood]').val(buscacep['bairro']);
                            $('input[name=city]').val(buscacep['localidade']);
                            $('input[name=uf]').val(buscacep['uf']);
                        }
                    }
                });
            });
        });
    </script>
@stop