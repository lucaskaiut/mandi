<html>
<head>
    <title>{{$pdfFileName}}</title>
</head>
<body>
<pre>
<img style=" margin-left: 35%;" width="150"
     src="{{public_path('images/logo_dynamo.png')}}">
<h1 style="margin: 0;">Ficha Cadastral - {{strtok($athleteOne->name, " ")}} ({{$athleteOne->matricula}})</h1>
<h3 style="margin-bottom: -5px; margin-top: 5px">Dados do Atleta</h3>
<b>Nome:</b> {{$athleteOne->name}}

<b>RG:</b> {{$athleteOne->rg}}

<b>Data de Nascimento:</b> {{date('d/m/Y', strtotime($athleteOne->birth))}}

<b>Sexo:</b> @if($athleteOne->gender == 'm')Masculino @else Feminino @endif


<b>E-Mail:</b> {{$athleteOne->email}}

<b>Telefone/Celular:</b> {{$athleteOne->number_phone}}

<b>Posição:</b> {{$athleteOne->position}}

<b>Categoria:</b> {{$athleteOne->athlete_category}}

<hr>
<h3 style="margin-bottom: -5px;">Dados do responsável</h3>
<b>Nome:</b> {{$athleteOne->parents_name}}

<b>RG:</b> {{$athleteOne->parents_rg}}

<b>Telefone/Celular:</b> {{$athleteOne->parents_number_phone}}

<hr>
<h3 style="margin-bottom: -5px;">Endereço</h3>
<b>Rua:</b> {{$athleteOne->address}}

<b>Número:</b> {{$athleteOne->number}}

<b>Bairro:</b> {{$athleteOne->neighborhood}}

<b>Cidade:</b> {{$athleteOne->city}}

<b>Estado:</b> {{$athleteOne->uf}}

<hr>
<h3 style="margin-bottom: -5px;">Dados da Anamnese</h3>

<b>Altura: </b>{{$athleteOne->height}}

<b>Peso: </b>{{$athleteOne->weight}}

<b>Possui alguma restrição na prática de atividade física?</b>@if($athleteOne->phys_restriction == 1) {{$athleteOne->phys_restriction_name}} @else Não @endif


<b>Atualmente sente alguma dor no corpo? </b>@if($athleteOne->body_pain == 1) {{$athleteOne->body_pain_location}} @else Não @endif


<b>Já perdeu a consciência como resultado de desmaio?</b>@if($athleteOne->faint == 1) Sim @else Não @endif


<b>Possui algum desvio postural?</b> {{$athleteOne->posture_deviation_name}}


<b>Possui alguma lesão óssea, lesão articular ou muscular? </b>@if($athleteOne->bone_injury == 1) {{$athleteOne->bone_injury_name}} @else Não @endif


<b>Foi submetido a algum tipo de cirurgia?</b>@if($athleteOne->surgery == 1) {{$athleteOne->surgery_name}} @else Não @endif


<b>Possui alguma deficiência ou limitação física?</b>@if($athleteOne->physical_disability == 1) {{$athleteOne->physical_disability_name}} @else Não @endif


<b>Pratica algum tipo de atividade física?</b>@if($athleteOne->exercise == 1) {{$athleteOne->exercise_name}} @else Não @endif


<b>Como você considera sua alimentação</b> {{$athleteOne->feeding}}


<b>Tem histórico de Alcoolísmo, Tabagismo ou Sedentarismo? </b>@if($athleteOne->exercise == 1) {{$athleteOne->exercise_name}} @else Não @endif


<b>Tem alguma doença de tratamento contínuo?</b>@if($athleteOne->disease == 1) {{$athleteOne->disease_name}} @else Não @endif


<b>Tem histórico familiar de doenças coronarianas e/ou hipertensão?</b>@if($athleteOne->family_disease == 1) {{$athleteOne->family_who_obs}} @else Não @endif


<b>Faz uso de medicamentos contínuos?</b>@if($athleteOne->drug == 1) {{$athleteOne->drug_name}} @else Não @endif


<b>Gravidez atual ou nos últimos 3 meses?</b>@if($athleteOne->recent_pregnancy == 1) {{$athleteOne->pregnancy_number}} @else Não @endif

@if($athleteOne->obs != null)
<b>Observações: </b>{{$athleteOne->obs}}
@endif

</pre>
</body>
</html>