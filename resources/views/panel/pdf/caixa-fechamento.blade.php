<html>
<head>
    <style>
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
            background-color: #f39c12;
            color: white;
        }
        .table-custom .entrada td {
            color: green;
        }
        .table-custom .saida td {
            color: red;
        }
    </style>
</head>
<body>
<table class="table-custom">
    <thead>
    <tr>
        <th>Sequência</th>
        <th>Referência</th>
        <th>Documento</th>
        <th>Descrição</th>
        <th>Valor</th>
        <th>Entrada/Saída</th>
        <th>Tipo de Transação</th>
        <th>Data</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cashierHistories as $history)
        <tr @if($history->entrada == 0)class="saida" @else class="entrada" @endif>
            <td>{{$history->id}}</td>
            <td>{{$history->referencia}}</td>
            <td>{{$history->documento}}</td>
            <td>{{$history->descricao}}</td>
            <td>R${{number_format($history->valor, 2, ',', '.')}}</td>
            <td>@if($history->entrada == 1) Entrada @else Saída @endif</td>
            <td>{{$history->pag_tipo}}</td>
            <td>{{ date( 'd/m/Y' , strtotime($history->created_at))}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<div style="text-align: right; font-size: 12px; margin-top: 50px;">
    <p><b>Saldo Inicial: </b>R${{number_format($caixaMovimentos['inicial'], 2, ',', '.')}}</p>
    <p><b>Entradas: </b>R${{number_format($caixaMovimentos['entradas'], 2, ',', '.')}}</p>
    <p><b>Saídas: </b>R${{number_format($caixaMovimentos['saidas'], 2, ',', '.')}}</p>
    <p><b>Saldo Final: </b>R${{number_format($caixaMovimentos['final'], 2, ',', '.')}}</p>
</div>
</body>
</html>