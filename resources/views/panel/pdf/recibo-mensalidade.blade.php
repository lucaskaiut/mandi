<html>
<head>
    <title>{{$pdfFileName}}</title>
</head>
<body>
<style>
    body {
        font-size: small;
    }
    .border{
        border-style: solid;
        border-width: 3px;
        border-radius: 60px;
    }
</style>
<pre>
    <div class="border">
        <h1 style="margin: 0;">    RECIBO Nº {{$mensalidade['recibo']}}     VALOR R${{number_format($mensalidade['amount'], 2, ',', '.')}}</h1>

        <b>Recebi(emos)de </b>{{$mensalidade['atleta']}}

        <b>A quantia de {{$valueExt}}</b>

        <b>Correspondente a</b> MENSALIDADE {{$mensalidade['ref_mes']}} PAGO EM {{$mensalidade['pag_tipo']}}

        <b>e para clareza firmo(amos) o presente</b>

        <b>Clube Dynamo de Voleibol</b><img style=" margin-left: 200px;" width="150" src="{{public_path('images/logo_dynamo.png')}}">

        <b>Endereço: </b>RUA ACRE, 710 - BONECA DO IGUAÇU, SÃO JOSÉ DOS PINHAIS - PR

        <small>{{$date}}</small>

    </div>
</pre>

<hr>

<pre>
    <div class="border">
        <h1 style="margin: 0;">    RECIBO Nº {{$mensalidade['recibo']}}     VALOR R${{number_format($mensalidade['amount'], 2, ',', '.')}}</h1>

        <b>Recebi(emos)de </b>{{$mensalidade['atleta']}}

        <b>A quantia de {{$valueExt}}</b>

        <b>Correspondente a</b> MENSALIDADE {{$mensalidade['ref_mes']}} PAGO EM {{$mensalidade['pag_tipo']}}

        <b>e para clareza firmo(amos) o presente</b>

        <b>Clube Dynamo de Voleibol</b><img style=" margin-left: 200px;" width="150" src="{{public_path('images/logo_dynamo.png')}}">

        <b>Endereço: </b>RUA ACRE, 710 - BONECA DO IGUAÇU, SÃO JOSÉ DOS PINHAIS - PR

        <small>{{$date}}</small>

    </div>
</pre>
</body>
</html>