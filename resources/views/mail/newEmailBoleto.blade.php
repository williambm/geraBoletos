
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Email de notificação - Boleto EEUSP</title>
</head>
<body>
    <table>
        <tr>
            <th><img src="{{ URL::to('.\assets\logoEE.jpg') }}" alt="Logotipo EEUSP" width="80" height="80" style="display:inline;"></th>
            <th><u><h3 style="display:inline; margin: 35px; padding-top: -20;">Sistema Gera Boletos - EEUSP</h3></u></th>
        </tr>        
    </table>
    <br>
        <p>Prezadx <b>{{$consumidor->nome}}</b>, o seu boleto foi gerado com sucesso !</p>
        <p>Evento: <b style="background-color:#DCDCDD;">{{$nomeEvento}}</b></p>
        <br>
        <div>
            <button style="
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
            border: 1px solid transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
            transition: color .15s
            ">
                <a  style="text-decoration: none; color: #fff;" href="{{route('admin.consumidor.segundaVia',['codBoletoGerado'=>$consumidor->rastreioBoleto_id])}}" class="btn btn-info btn-block"><b>CLIQUE AQUI para Obter o seu Boleto</b></a>
            </button>
        </div>
    <br>
    <br>
        <a href="https://uspdigital.usp.br/mercurioweb/merBoletoBancarioAcompanhar.jsp">Caso necessite retirar uma segunda via do seu boleto, você também pode o fazer pelos Sistemas USP através deste link !</a>
    <br>
    <hr>
    <p>Por favor <b style="color:red;">NÃO RESPONDA este e-mail</b>. Esta é uma comunicação gerada de forma automática por nosso sistema de Boletos.</p>
</body>
</html>
