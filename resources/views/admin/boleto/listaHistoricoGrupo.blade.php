@extends('layouts.app')
@section('content')   
 <!--DashBoard é apenas uma view que recebe dados de loginCtrler e monta a página para o administrador do sistema  -->
    <div class="container">
        <div class="row">
            <h5>Histórico Completo de Boletos do Grupo - {{$grupo[0]->nome}}</h5>
        </div>   
        <br> 
        <table class='table-responsive table-striped'>
            <thead>
                <tr>
                    <th class='col-3'>Evento</th>
                    <th class='col-2'>Data de Expiração</th>
                    <th class='col-2'>Link de Divulgação</th>
                    <th>Ação</th>                            
                </tr>
            </thead>
            <tbody>
                @foreach($boletos as $boleto)                       
                    <tr>
                        <td class='col-3'>{{$boleto->nomeEvento}}</td>
                        <td class='col-2'>{{date("d/m/Y", strtotime($boleto->fimDataPublicacao))}}</td>
                        <td class='col-2'>
                            <a href="{{config('linkPublicacao.link').$boleto->id}}" target="_blank">
                                {{config('linkPublicacao.link').$boleto->id}}
                            </a>
                        </td>
                        <td>
                            <div class="btn-group col-4">
                                
                                <!-- Editar
                                <a href="#" class="btn btn-info" data-toggle="tooltip" title="Editar conf. boleto">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                    </svg>
                                </a> -->
                                
                                <!-- Copiar configurações -->
                                <a href="{{route('admin.boleto.copyConf',['boleto'=>$boleto->id])}}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Copiar conf. boleto">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-stickies" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M0 1.5A1.5 1.5 0 0 1 1.5 0H13a1 1 0 0 1 1 1H1.5a.5.5 0 0 0-.5.5V14a1 1 0 0 1-1-1V1.5z"/>
                                        <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 0 1 3.5 2h11A1.5 1.5 0 0 1 16 3.5v6.086a1.5 1.5 0 0 1-.44 1.06l-4.914 4.915a1.5 1.5 0 0 1-1.06.439H3.5A1.5 1.5 0 0 1 2 14.5v-11zM3.5 3a.5.5 0 0 0-.5.5v11a.5.5 0 0 0 .5.5h6.086a.5.5 0 0 0 .353-.146l4.915-4.915A.5.5 0 0 0 15 9.586V3.5a.5.5 0 0 0-.5-.5h-11z"/>
                                        <path fill-rule="evenodd" d="M10.5 10a.5.5 0 0 0-.5.5v5H9v-5A1.5 1.5 0 0 1 10.5 9h5v1h-5z"/>
                                    </svg>
                                </a>                                
                            </div>  
                        </td>                           
                    </tr>                    
                @endforeach
            </tbody>
        </table>
        <br>
        <div class="row text-center">
            <!-- Links de Paginação -->
            {{$boletos->links()}}
        </div>        
    </div>
@endsection