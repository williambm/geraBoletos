@extends('layouts.consumidor')
<!-- Página para pós geração de boleto com $cosumidor e $flag do tipo de página -->
@section('content')
@if($flagSituacao == 'segundaVia')
    <div class="col-10">
        <p>Prezadx <b>{{$consumidor->nome}}</b>, você já possui um boleto gerado para este evento.</p>
    </div>
    <div class="col-10">    
        <p>Para retirar uma segunda via deste boleto por favor utilize o código: <b style="color:red;">{{$consumidor->rastreioBoleto_id}}</b></p>
    </div>
    <div class="col text-center">
        <a href="{{route('admin.consumidor.segundaVia',['codBoletoGerado'=>$consumidor->rastreioBoleto_id])}}" class="btn btn-info btn-block"><b>Obter segunda via</b></a>
    </div>
    <br>
    <div class="col text-center">
        <a href="https://uspdigital.usp.br/mercurioweb/merBoletoBancarioAcompanhar.jsp">Você pode tentar também obter uma segunda via do seu boleto já gerado através dos sistemas USP - Clique Aqui !</a>
    </div>

@elseif($flagSituacao == 'primeiraVia')
<div class="col-10">
    <p>Prezadx <b>{{$consumidorMail->nome}}</b>, seu boleto foi gerado com sucesso!</p>
</div>
<div class="col-10">
    <p>O código de seu boleto gerado é: <b style="color:red;">{{$consumidorMail->rastreioBoleto_id}}</b></p>
</div>
<div class="col text-center">
    <a href="{{route('admin.consumidor.segundaVia',['codBoletoGerado'=>$consumidorMail->rastreioBoleto_id])}}" class="btn btn-info btn-block"><b>Obter Boleto</b></a>
</div>    
@endif
@endsection