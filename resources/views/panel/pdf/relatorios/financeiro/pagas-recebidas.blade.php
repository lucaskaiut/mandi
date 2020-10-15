<html>
<head>
    <style>
        body{
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        }
        .table-custom {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .table-custom td, .table-custom th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table-custom tr:nth-child(even){background-color: #f2f2f2;}

        .table-custom tr:hover {background-color: #ddd;}

        .table-custom th {
            padding-top: 4px;
            padding-bottom: 4px;
            text-align: left;
            background-color: #193879;
            color: white;
        }
        .title-h1{
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            text-align: center;
            text-transform: uppercase;
        }
    </style>
    <title>{{$pdfFileName}}</title>
</head>
<body>
<h1 class="title-h1">Relatório de {{$titulo}}</h1>
<h3 class="title-h1">Contas Pagas</h3>
<table class="table-custom">
    <thead>
    <tr>
        <th>Sequência</th>
        <th>Descrição</th>
        <th>Valor</th>
        <th>Vencimento</th>
        <th>Lançamento</th>
    </tr>
    </thead>
    <tbody>
    @foreach($contasRecebidas as $invcoice)
        <tr>
            <td>{{$invcoice->id}}</td>
            <td>{{$invcoice->descricao}}</td>
            <td>R${{number_format($invcoice->valor, 2, ',', '.')}}</td>
            <td>{{ date( 'd/m/Y' , strtotime($invcoice->vencimento))}}</td>
            <td>{{ date( 'd/m/Y' , strtotime($invcoice->created_at))}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<div style="text-align: right; font-size: 12px; margin-top: 50px;">
    <p><b>{{$totalRegisterRecebidas}} Registro(s) encontrado(s)</b></p>
</div>
<hr>
<h3 class="title-h1">Contas Recebidas</h3>
<table class="table-custom">
    <thead>
    <tr>
        <th>Sequência</th>
        <th>Descrição</th>
        <th>Valor</th>
        <th>Vencimento</th>
        <th>Lançamento</th>
    </tr>
    </thead>
    <tbody>
    @foreach($contasPagas as $invcoice)
        <tr>
            <td>{{$invcoice->id}}</td>
            <td>{{$invcoice->descricao}}</td>
            <td>R${{number_format($invcoice->valor, 2, ',', '.')}}</td>
            <td>{{ date( 'd/m/Y' , strtotime($invcoice->vencimento))}}</td>
            <td>{{ date( 'd/m/Y' , strtotime($invcoice->created_at))}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<div style="text-align: right; font-size: 12px; margin-top: 50px;">
    <p><b>{{$totalRegisterPagas}} Registro(s) encontrado(s)</b></p>
</div>
<hr>
<div style="text-align: left; font-size: 12px; margin-top: 50px;">
    <p><b>Total Recebido: </b>R${{number_format($valorRecebido, 2, ',', '.')}}</p>
    <p><b>Total Pago: </b>R${{number_format($valorPago, 2, ',', '.')}}</p>
    <p>
        <b>Lucro: </b><strong @if($lucro < 0) style="color: red;" @elseif($lucro > 0) style="color: green;" @endif>R${{number_format($lucro, 2, ',', '.')}}</strong>
    </p>
</div>
</body>
</html>