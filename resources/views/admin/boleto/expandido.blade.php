@extends('layouts.app')


@section('content')
    <h4>Relação de Inscritos</h4>
    <hr>
    <p>Nome do Evento: <b>{{$nomeEvento}}</b></p>
    <p>Quantidade de Pessoas: <b>{{count($consumidoresTratado)}}</b></p>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th scope='col'>Nome</th>
                <th scope='col'>E-mail</th>
                <th scope='col'>Status de Pagamento</th>
                <th scope='col'>Ações</th>                            
            </tr>
        </thead>
        <tbody>
        @foreach($consumidoresTratado as $consumidor)
            <tr>                
                <td>
                    <a href="{{route('admin.consumidor.show',['consumidor'=>$consumidor['id']])}}">
                        {{$consumidor['nome']}}
                    </a>
                </td>
                <td>{{$consumidor['email']}}</td>
                <td>{{$consumidor['statusPGTO']}}</td>
                @if($consumidor['statusPGTO'] == "Emitido" || $consumidor['statusPGTO'] == "Verificar")
                    <td>
                        <a href="{{route('admin.consumidor.segundaVia',['codBoletoGerado'=>$consumidor['rastreioBoleto_id']])}}" class="btn btn-info btn-block" data-toggle="tooltip" title="Imprimir segunda via do boleto">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-printer-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5z"/>
                                <path fill-rule="evenodd" d="M11 9H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
                                <path fill-rule="evenodd" d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                            </svg>
                        </a>
                    </td>
                @else
                    <td></td>
                @endif
            </tr>
        @endforeach
    </table>
@endsection