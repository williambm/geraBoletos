@extends('layouts.app')

@section('content')

    <h4>Dados Detalhados - Pessoa</h4>
    <hr>

    <p style="margin-bottom: 0;"><b>Nome: </b>{{$consumidor->nome}}</p>
    <p><b>CPF: </b>{{$consumidor->cpf}}</p>
    <div>
        <u><b>Dados de contato</b></u>
        <p style="margin-bottom: 0;"><b>e-mail: </b>{{$consumidor->email}}</p>
        <p><b>telefone: </b>{{$consumidor->telefone}}</p>
    </div>
    <div>
        <u><b>Endereço</b></u>
        <p style="margin-bottom: 0;"><b>Endereço: </b>{{$consumidor->endereco}} <b>Nº</b> {{$consumidor->numEndereco}}</p>
        <p><b>Complemento: </b>{{$consumidor->complEndereco}}</p>
    </div>
    <div>        
        <table class='table table-striped'>
            <thead>
                <tr>
                <th data-toggle="tooltip" title="Situação de pagamento do boleto">Situação</th>
                <th>Valor cobrado</th>
                <th>Valor pago</th>
                <th data-toggle="tooltip" title="Data de vencimento do boleto">Data de Vencimento</th>
                <th data-toggle="tooltip" title="Data em que o boleto para esta pessoa foi emitido">Data de Registro</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$pgtoTratado['situacao']}}</td>
                    <td>R$ {{$pgtoTratado['valorCobrado']}}</td>
                    <td>R$ {{$pgtoTratado['valorPago']}}</td>
                    <td>{{$pgtoTratado['dataVenc']}}</td>
                    <td>{{$pgtoTratado['dataRegistro']}}</td>
                </tr>
            </tbody>
        </table>
    </div>

@endsection