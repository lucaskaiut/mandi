@extends('panel.main')

@section('title', 'Dynamo Voleibol')
<style>
    #blanket, #aguarde {
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

    #aguarde {
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
    <h1 class="title-h1">Cadastro de Atleta</h1>
    <div style="margin-left: 40%" ;>
        @can('athlete-list')
            <a target="_blank"  href="{{route('pdf', ['id' => $data->id])}}">
                <button type="button" class="btn btn-info"><i class="fas fa-file-pdf"></i> Gerar PDF</button>
            </a>
            <a href="{{route('athlete.mail.pdf', ['id' => $data->id])}}">
                <button type="button" id="sendMail" class="btn btn-info submitMail"><i class="fas fa-envelope"></i> Enviar PDF</button>
            </a>
        @endcan
        @can('fin-list')
            <a href="{{route('athlete.mensalidades', ['id' => $data->id])}}">
                <button type="button" class="btn btn-primary"><i class="fas fa-calendar-alt"></i> Mensalidades</button>
            </a>
        @endcan
        @can('athlete-delete')
            <a href="{{route('delete', ['id' => $data->id])}}">
                <button type="button" id="delete" class="btn btn-danger"> <i class="fas fa-trash-alt"></i> Apagar usuário
                </button>
            </a>
        @endcan
    </div>
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
                <form method="POST" action="{{route('update', ['id' => $data->id])}}">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="{{$data->id}}">
                    <input type="hidden" name="matricula" value="{{$data->matricula}}">
                    <div class="col-lg-12">
                        <h3>Dados do Atleta</h3>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group col-lg-8 col-form-label">
                            <label>Nome do atleta<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="name" value="{{$data->name}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                        <div class="form-group col-lg-2">
                            <label>Posição</label>
                            <input type="text" class="form-control" name="position" value="{{$data->position}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="exampleFormControlSelect1">Sexo</label>
                            <select class="form-control" name="gender" id="exampleFormControlSelect1"
                                    @cannot('athlete-edit') readonly="" @endcannot>
                                <option value="m">Masculino</option>
                                <option value="f">Feminino</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group col-lg-4 col-md-4">
                            <label>Unidade<b style="color: red;">*</b></label>
                            <select class="form-control" name="company_id" id="company">
                                @foreach($empresas as $empresa)
                                    <option value="{{$empresa->id}}" @if ($data->empresa == $empresa->apelido) selected @endif>{{$empresa->apelido}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <label>Categoria</label>
                            <select class="form-control" name="athlete_category" id="category"
                                    @cannot('athlete-edit') readonly="" @endcannot>
                                <option value="">Selecione a categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}"
                                            @if($data->athlete_category == $category->categoria) selected @endif>
                                        {{$category->categoria}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group col-lg-3 col-md-4">
                            <label>Data de nascimento<b style="color: red;">*</b></label>
                            <input type="date" class="form-control" name="birth" value="{{$data->birth}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                        <div class="form-group col-lg-4 col-md-5">
                            <label>E-Mail</label>
                            <input type="email" class="form-control" name="email" value="{{$data->email}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                        <div class="form-group col-lg-3 col-md-6">
                            <label>Telefone<b style="color: red;">*</b></label>
                            <input data-mask="(00) 00000-0000" type="text" class="form-control" name="number_phone" value="{{$data->number_phone}}"
                                   maxlength="11" @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                        <div class="form-group col-lg-2 col-md-6">
                            <label>RG<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="rg" value="{{$data->rg}}" maxlength="11"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group col-lg-3 col-md-5">
                            <label>CEP<b style="color: red;">*</b></label>
                            <input data-mask="00.000-000" type="text" class="form-control" name="cep" id="cep" value="{{$data->cep}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                        <div class="form-group col-lg-6 col-md-10">
                            <label>Rua<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="address" value="{{$data->address}}" readonly="">
                        </div>
                        <div class="form-group col-lg-2 col-md-2">
                            <label>Número<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="number" maxlength="5" value="{{$data->number}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label>Bairro<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="neighborhood" value="{{$data->neighborhood}}" readonly="">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group col-lg-6 col-md-4">
                            <label>Cidade<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="city" value="{{$data->city}}" readonly="">
                        </div>
                        <div class="form-group col-lg-2 col-md-2">
                            <label>Estado<b style="color: red;">*</b></label>
                            <input type="text" class="form-control" name="uf" value="{{$data->uf}}" maxlength="2" readonly="">
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
                                   placeholder="Nome do responsável"
                                   value="{{$data->parents_name}}" @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                        <div class="form-group col-lg-3 col-md-3">
                            <label>Telefone</label>
                            <input data-mask="(00) 00000-0000" type="text" class="form-control" name="parents_number_phone"
                                   placeholder="Número do responsável"
                                   value="{{$data->parents_number_phone}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                        <div class="form-group col-lg-3 col-md-3">
                            <label>RG</label>
                            <input type="text" class="form-control" name="parents_rg" placeholder="RG do responsável"
                                   value="{{$data->parents_rg}}" @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <h3>Dados da Anamnese do Atleta</h3>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group col-lg-2 col-md-2">
                            <label>Altura<b style="color: red;">*</b></label>
                            <input data-mask="0,00" type="text" class="form-control" name="height" value="{{$data->height}}"
                                   maxlength="3"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                        <div class="form-group col-lg-2 col-md-2">
                            <label>Peso<b style="color: red;">*</b></label>
                            <input data-mask="#00,00" data-mask-reverse="true" type="text" class="form-control" name="weight" value="{{$data->weight}}"
                                   maxlength="6"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="form-group col-md-12 row">
                        <label class="col-lg-3 col-md-7">Possui alguma restrição na prática de atividade física?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="phys_restriction" value="1"
                                       @if($data->phys_restriction == 1)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="phys_restriction" value="0"
                                       @if($data->phys_restriction == 0)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Não
                            </label>
                        </div>
                        <div class="form-group col-lg-4">
                            <input type="text" class="form-control" name="phys_restriction_name"
                                   value="{{$data->phys_restriction_name}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-lg-3 col-md-7">Atualmente sente dor em alguma região do corpo?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="body_pain" value="1" @if($data->body_pain == 1)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="body_pain" value="0" @if($data->body_pain == 0)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Não
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" name="body_pain_location"
                                   value="{{$data->body_pain_location}}" @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="form-group col-lg-12 col-md-12">
                        <label class="col-lg-3 col-md-7">Já perdeu a consciência como resultado de desmaio?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="faint" value="1" @if($data->faint == 1)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="faint" value="0" @if($data->faint == 0)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Não
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-lg-3 col-md-5">Possui algum tipo de desvio postural?</label>
                        <div class="form-group col-lg-4 col-md-4">
                            <select name="posture_deviation_name" class="form-control"
                                    @cannot('athlete-edit') readonly="" @endcannot>
                                <option value="Não">Não</option>
                                <option value="Lordose Cervical"
                                        @if($data->posture_desviation_name == 'Lordose Cervical')selected=""@endif>
                                    Lordose
                                    Cervical
                                </option>
                                <option value="Lordose Lombar"
                                        @if($data->posture_desviation_name == 'Lordose Lombar')selected=""@endif>Lordose
                                    Lombar
                                </option>
                                <option value="Cifose Dorsal"
                                        @if($data->posture_desviation_name == 'Cifose Dorsal')selected=""@endif>Cifose
                                    Dorsal
                                </option>
                                <option value="Escoliose"
                                        @if($data->posture_desviation_name == 'Escoliose')selected=""@endif>
                                    Escoliose
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-lg-3 col-md-7">Possui alguma lesão óssea, lesão articular ou muscular?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="bone_injury" value="1" @if($data->bone_injury == 1)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="bone_injury" value="0" @if($data->bone_injury == 0)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Não
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" name="bone_injury_name"
                                   value="{{$data->bone_injury_name}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-lg-3 col-md-6">Foi submetido a algum tipo de cirurgia?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="surgery" value="1" @if($data->surgery == 1)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="surgery" value="0" @if($data->surgery == 0)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Não
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" name="surgery_name" value="{{$data->surgery_name}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-lg-3 col-md-6">Possui alguma deficiência ou limitação física?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="physical_disability" value="1"
                                       @if($data->physical_disability == 1)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="physical_disability" value="0"
                                       @if($data->physical_disability == 0)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Não
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" name="physical_disability_name"
                                   value="{{$data->physical_disability_name}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-lg-3 col-md-6">Pratica algum tipo de atividade física?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="exercise" value="1" @if($data->exercise == 1)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="exercise" value="0" @if($data->exercise == 0)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Não
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" name="exercise_name"
                                   value="{{$data->exercise_name}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-lg-3 col-md-6">Como você considera sua alimentação?<b style="color: red;">*</b></label>
                        <div class="form-group col-lg-3 col-md-6">
                            <label class="radio-inline">
                                <input type="radio" name="feeding" value="Adequada"
                                       @if($data->feeding == 'Adequada')checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Adequada
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="feeding" value="Inadequada"
                                       @if($data->feeding == 'Inadequada')checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Inadequada
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="feeding" value="Regular"
                                       @if($data->feeding == 'Regular')checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Regular
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-lg-3 col-md-8">Tem histórico de Alcoolísmo, Tabagismo ou Sedentarismo?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="addiction" value="1" @if($data->addiction == 1)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="addiction" value="0" @if($data->addiction == 0)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Não
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" name="addiction_name"
                                   value="{{$data->addicition_name}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-lg-3 col-md-6">Tem alguma doença de tratamento contínuo?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="disease" value="1" @if($data->disease == 1)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="disease" value="0" @if($data->disease == 0)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Não
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" name="disease_name" value="{{$data->disease_name}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-lg-3 col-md-9">Tem histórico familiar de doenças coronarianas e/ou hipertensão?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-3">
                            <label class="radio-inline">
                                <input type="radio" name="family_disease" value="1"
                                       @if($data->family_disease == 1)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="family_disease" value="0"
                                       @if($data->family_disease == 0)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Não
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" name="family_who_obs"
                                   value="{{$data->family_who_obs}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-lg-3 col-md-6">Faz uso de medicamentos contínuos?<b style="color: red;">*</b></label>
                        <div class="fform-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="drug" value="1" @if($data->drug == 1)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="drug" value="0" @if($data->drug == 0)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Não
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" name="drug_name" value="{{$data->drug_name}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="form-group col-md-12 ">
                        <label class="col-lg-3 col-md-6">Gravidez atual ou nos últimos 3 meses?<b
                                    style="color: red;">*</b></label>
                        <div class="form-group col-lg-2 col-md-4">
                            <label class="radio-inline">
                                <input type="radio" name="recent_pregnancy" value="1"
                                       @if($data->recent_pregnancy == 1)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Sim
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="recent_pregnancy" value="0"
                                       @if($data->recent_pregnancy == 0)checked=""
                                       @endif @cannot('athlete-edit') readonly="" @endcannot> Não
                            </label>
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" name="pregnancy_number"
                                   value="{{$data->pregnancy_number}}"
                                   @cannot('athlete-edit') readonly="" @endcannot>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="exampleFormControlTextarea2">Informações complementares</label>
                        <textarea class="form-control rounded-0" name="obs" id="exampleFormControlTextarea2" rows="3"
                                  value=""
                                  @cannot('athlete-edit') readonly="" @endcannot>{{$data->obs}}</textarea>
                    </div>
                    @can('athlete-edit')
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    @endcan
                </form>
            </div>
        </div>
    </div>
    <div id="blanket"></div>
    <div id="aguarde">Gerando arquivo e enviado email</div>

    <script>
        $(document).ready(function () {
            $('#cep').blur(function(){
                var cep = jQuery('#cep').val();
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
        $(document).ready(function() {
            $('.submitMail').click(function(){
                $('#aguarde, #blanket').css('display','block');
            });
        });
        $(document).ready(function(){
            $("#delete").click(function(){
                if (!confirm("Tem certeza que deseja apagar esse usuário?")){
                    return false;
                }
            });
        });

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
    </script>
@stop