@extends('layouts.layout')
<h1 style="text-align: center;">Cadastro de Atleta</h1>
@if(isset($errors) && count($errors)>0)
    <div class="alert alert-danger">
        <p>Todos os campos com * são obrigatórios</p>
    </div>
@endif
<div class="container col-md-10">
    <form method="POST" action="{{route('store.athlete')}}">
        {{csrf_field()}}
        <h3>Dados do Atleta</h3>
        <div class="row">
            <div class="form-group col-md-6">
                <label>Nome do atleta<b style="color: red;">*</b></label>
                <input type="text" class="form-control" name="name" placeholder="Nome" required>
            </div>
            <div class="form-group col-md-4">
                <label>Posição</label>
                <input type="text" class="form-control" name="position" placeholder="Posição">
            </div>
            <div class="form-group col-md-2">
                <label for="exampleFormControlSelect1">Sexo</label>
                <select class="form-control" name="gender" id="exampleFormControlSelect1">
                    <option value="m">Masculino</option>
                    <option value="f">Feminino</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-2">
                <label>Data de nascimento<b style="color: red;">*</b></label>
                <input type="date" class="form-control" name="birth" placeholder="Data de nascimento" required>
            </div>
            <div class="form-group col-sm-4">
                <label>E-Mail<b style="color: red;">*</b></label>
                <input type="email" class="form-control" name="email" placeholder="E-Mail" required>
            </div>
            <div class="form-group col-sm-3">
                <label>Telefone<b style="color: red;">*</b></label>
                <input type="text" class="form-control" name="number_phone" placeholder="Telefone/Celular" maxlength="11"
                       required>
            </div>
            <div class="form-group col-sm-3">
                <label>RG<b style="color: red;">*</b></label>
                <input type="text" class="form-control" name="rg" placeholder="RG" maxlength="11" required>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-7">
                <label>Rua<b style="color: red;">*</b></label>
                <input type="text" class="form-control" name="address" placeholder="Rua" required>
            </div>
            <div class="form-group col-sm-1">
                <label>Número<b style="color: red;">*</b></label>
                <input type="number" class="form-control" name="number" placeholder="Número" required>
            </div>
            <div class="form-group col-sm-4">
                <label>Bairro<b style="color: red;">*</b></label>
                <input type="text" class="form-control" name="neighborhood" placeholder="Bairro" required>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-11">
                <label>Cidade<b style="color: red;">*</b></label>
                <input type="text" class="form-control" name="city" placeholder="Cidade" required>
            </div>
            <div class="form-group col-sm-1">
                <label>Estado<b style="color: red;">*</b></label>
                <input type="text" class="form-control" name="uf" placeholder="UF" maxlength="2" required>
            </div>
        </div>
        <h3>Dados do responsável
            <small>(caso atleta seja menor de idade)</small>
        </h3>
        <div class="row">
            <div class="form-group col-sm-6">
                <label>Nome</label>
                <input type="text" class="form-control" name="parents_name" placeholder="Nome do responsável">
            </div>
            <div class="form-group col-sm-3">
                <label>Telefone</label>
                <input type="text" class="form-control" name="parents_number_phone" placeholder="Número do responsável">
            </div>
            <div class="form-group col-sm-3">
                <label>RG</label>
                <input type="text" class="form-control" name="parents_rg" placeholder="RG do responsável">
            </div>
        </div>
        <h3>Dados da Anamnese do Atleta</h3>
        <div class="row">
            <div class="form-group col-sm-2">
                <label>Altura<b style="color: red;">*</b></label>
                <input type="text" class="form-control" name="height" placeholder="Altura" maxlength="3">
            </div>
            <div class="form-group col-sm-2">
                <label>Peso<b style="color: red;">*</b></label>
                <input type="text" class="form-control" name="weight" placeholder="Peso" maxlength="4">
            </div>
        </div>
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Possui alguma restrição na prática de atividade física?<b style="color: red;">*</b></label>
            <div class="form-group col-sm-1">
                <label class="radio-inline">
                    <input type="radio" name="phys_restriction" value="1"> Sim
                </label>
                <label class="radio-inline">
                    <input type="radio" name="phys_restriction" value="0"> Não
                </label>
            </div>
            <div class="form-group col-sm-4">
                <input type="text" class="form-control" name="phys_restriction_name" placeholder="Qual(is)?">
            </div>
        </div>
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Atualmente sente dor em alguma região do corpo?<b style="color: red;">*</b></label>
            <div class="form-group col-sm-1">
                <label class="radio-inline">
                    <input type="radio" name="body_pain" value="1"> Sim
                </label>
                <label class="radio-inline">
                    <input type="radio" name="body_pain" value="0"> Não
                </label>
            </div>
            <div class="form-group col-sm-4">
                <input type="text" class="form-control" name="body_pain_location" placeholder="Onde?">
            </div>
        </div>
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Já perdeu a consciência como resultado de desmaio?<b
                        style="color: red;">*</b></label>
            <div class="form-group col-sm-1">
                <label class="radio-inline">
                    <input type="radio" name="faint" value="1"> Sim
                </label>
                <label class="radio-inline">
                    <input type="radio" name="faint" value="0"> Não
                </label>
            </div>
        </div>
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Possui algum tipo de desvio postural?</label>
            <div class="form-group col-sm-4">
                <select name="posture_deviation_name" class="form-control">
                    <option value="Não">Não</option>
                    <option value="Lordose Cervical">Lordose Cervical</option>
                    <option value="Lordose Lombar">Lordose Lombar</option>
                    <option value="Cifose Dorsal">Cifose Dorsal</option>
                    <option value="Escoliose">Escoliose</option>
                </select>
            </div>
        </div>
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Possui alguma lesão óssea, lesão articular ou muscular?<b style="color: red;">*</b></label>
            <div class="form-group col-sm-1">
                <label class="radio-inline">
                    <input type="radio" name="bone_injury" value="1"> Sim
                </label>
                <label class="radio-inline">
                    <input type="radio" name="bone_injury" value="0"> Não
                </label>
            </div>
            <div class="form-group col-sm-4">
                <input type="text" class="form-control" name="bone_injury_name" placeholder="Local:">
            </div>
        </div>
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Foi submetido a algum tipo de cirurgia?<b style="color: red;">*</b></label>
            <div class="form-group col-sm-1">
                <label class="radio-inline">
                    <input type="radio" name="surgery" value="1"> Sim
                </label>
                <label class="radio-inline">
                    <input type="radio" name="surgery" value="0"> Não
                </label>
            </div>
            <div class="form-group col-sm-4">
                <input type="text" class="form-control" name="surgery_name" placeholder="Qual(is)?">
            </div>
        </div>
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Possui alguma deficiência ou limitação física?<b style="color: red;">*</b></label>
            <div class="form-group col-sm-1">
                <label class="radio-inline">
                    <input type="radio" name="physical_disability" value="1"> Sim
                </label>
                <label class="radio-inline">
                    <input type="radio" name="physical_disability" value="0"> Não
                </label>
            </div>
            <div class="form-group col-sm-4">
                <input type="text" class="form-control" name="physical_disability_name" placeholder="Qual(is)?">
            </div>
        </div>
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Pratica algum tipo de atividade física?<b style="color: red;">*</b></label>
            <div class="form-group col-sm-1">
                <label class="radio-inline">
                    <input type="radio" name="exercise" value="1"> Sim
                </label>
                <label class="radio-inline">
                    <input type="radio" name="exercise" value="0"> Não
                </label>
            </div>
            <div class="form-group col-sm-4">
                <input type="text" class="form-control" name="exercise_name" placeholder="Qual(is)?">
            </div>
        </div>
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Como você considera sua alimentação?<b style="color: red;">*</b></label>
            <div class="form-group col-sm-3">
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
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Tem histórico de Alcoolísmo, Tabagismo ou Sedentarismo?<b style="color: red;">*</b></label>
            <div class="form-group col-sm-1">
                <label class="radio-inline">
                    <input type="radio" name="addiction" value="1"> Sim
                </label>
                <label class="radio-inline">
                    <input type="radio" name="addiction" value="0"> Não
                </label>
            </div>
            <div class="form-group col-sm-4">
                <input type="text" class="form-control" name="addiction_name" placeholder="Qual(is)?">
            </div>
        </div>
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Tem alguma doença de tratamento contínuo?<b style="color: red;">*</b></label>
            <div class="form-group col-sm-1">
                <label class="radio-inline">
                    <input type="radio" name="disease" value="1"> Sim
                </label>
                <label class="radio-inline">
                    <input type="radio" name="disease" value="0"> Não
                </label>
            </div>
            <div class="form-group col-sm-4">
                <input type="text" class="form-control" name="disease_name" placeholder="Qual(is)?">
            </div>
        </div>
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Tem histórico familiar de doenças coronarianas e/ou hipertensão?<b
                        style="color: red;">*</b></label>
            <div class="form-group col-sm-1">
                <label class="radio-inline">
                    <input type="radio" name="family_disease" value="1"> Sim
                </label>
                <label class="radio-inline">
                    <input type="radio" name="family_disease" value="0"> Não
                </label>
            </div>
            <div class="form-group col-sm-4">
                <input type="text" class="form-control" name="family_who_obs" placeholder="Descrição: ">
            </div>
        </div>
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Faz uso de medicamentos contínuos?<b style="color: red;">*</b></label>
            <div class="form-group col-sm-1">
                <label class="radio-inline">
                    <input type="radio" name="drug" value="1"> Sim
                </label>
                <label class="radio-inline">
                    <input type="radio" name="drug" value="0"> Não
                </label>
            </div>
            <div class="form-group col-sm-4">
                <input type="text" class="form-control" name="drug_name" placeholder="Qual(is)?">
            </div>
        </div>
        <div class="form-group col-sm-12 row">
            <label class="col-sm-3">Gravidez atual ou nos últimos 3 meses?<b style="color: red;">*</b></label>
            <div class="form-group col-sm-1">
                <label class="radio-inline">
                    <input type="radio" name="recent_pregnancy" value="1"> Sim
                </label>
                <label class="radio-inline">
                    <input type="radio" name="recent_pregnancy" value="0"> Não
                </label>
            </div>
            <div class="form-group col-sm-4">
                <input type="text" class="form-control" name="pregnancy_number" placeholder="Nº de gestações:">
            </div>
        </div>
        <div class="form-group col-sm-8">
            <label for="exampleFormControlTextarea2">Informações complementares</label>
            <textarea class="form-control rounded-0" name="obs" id="exampleFormControlTextarea2" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary ">Cadastrar</button>
    </form>
</div>
