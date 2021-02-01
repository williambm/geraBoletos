@extends('layouts.app')
@section('content')   
 <!--DashBoard é apenas uma view que recebe dados de loginCtrler e monta a página para o administrador do sistema  -->
 <!-- variáveis desta view 'pessoa','gruposDaPessoa','boletos','grupoSelecionado','temGrupo' -->
    <div class="container">
        @if(isset($onlyGestor))
            <div class="row">
                <div class="col-10">                
                    <p>Olá, <b>{{$pessoa[0]->nome}}</b>. Atualmente você é gestor(a) deste sistema e não tem grupo de trabalho.</p>
                </div>
            </div>
        @elseif(isset($temGrupo))
            <div class="row">
                <div class="col-10">                
                    <p>Olá, <b>{{$pessoa[0]->nome}}</b>. Você possui <b>{{$gruposDaPessoa->count()}}</b> grupo(s) de trabalho.</p>
                </div>
            </div>        
            <form action="{{route('dash')}}" method="GET">
                @csrf
                <div class="form-row" >
                <!-- style="border:1px solid black; padding:2px;" -->
                    <div class="col-4">
                        <label>Selecione em qual grupo você deseja trabalhar :</label>
                    </div>
                    <div class="col-4">
                        <select name="grupo" class="form-control">
                            @foreach($gruposDaPessoa as $grupo)
                                <option value="{{$grupo->id}}">{{$grupo->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-lg btn-success" style="height:40px;">Selecionar</button>
                    </div>
                </div> 
        
            </form>
            <hr>
            @if(isset($boletos))
                <!-- Lista boletos e cria novos sobre o grupo de trabalho selecionado acima !!! -->        
                <h5 align="center" ><u>Boletos Ativos - {{$grupoSelecionado[0]->nome}}</u></h5>
                <a href="{{route('admin.boleto.create')}}" class="btn btn-lg btn-success">Criar Novo Boleto</a>
                <table class='.table-responsive table-striped'>
                    <thead>
                        <tr>
                            <th class='col-3'>Evento</th>
                            <th class='col-2'>Data de Expiração</th>
                            <th class='col-2'>Link de Divulgação</th>
                            <th>Ações</th>                            
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($boletos as $boleto)                       
                        <tr @if(isset($boleto->limQtdeInscritos) && $boleto->consumidores()->count() >=  $boleto->limQtdeInscritos) style="border: 1px dashed red;" @endif >
                            <td class='col-3'>
                                <a href="{{route('admin.boleto.expandido',['boleto'=>$boleto->id])}}">
                                    {{$boleto->nomeEvento}}
                                </a>
                            </td>
                            <td class='col-2'>{{date("d/m/Y", strtotime($boleto->fimDataPublicacao))}}</td>
                            <td class='col-2'>
                                <a href="{{config('linkPublicacao.link').$boleto->id}}" target="_blank">
                                    {{config('linkPublicacao.link').$boleto->id}}
                                </a>
                            </td>
                            <td>
                                <div class="btn-group col-5">
                                    <!-- Editar -->
                                    <a href="{{route('admin.boleto.edit',['boleto'=>$boleto->id])}}" class="btn btn-info" data-toggle="tooltip" title="Editar conf. boleto">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                    </a>
                                    <!-- Copiar configurações -->
                                    <a href="{{route('admin.boleto.copyConf',['boleto'=>$boleto->id])}}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Copiar conf. boleto">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-stickies" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M0 1.5A1.5 1.5 0 0 1 1.5 0H13a1 1 0 0 1 1 1H1.5a.5.5 0 0 0-.5.5V14a1 1 0 0 1-1-1V1.5z"/>
                                            <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 0 1 3.5 2h11A1.5 1.5 0 0 1 16 3.5v6.086a1.5 1.5 0 0 1-.44 1.06l-4.914 4.915a1.5 1.5 0 0 1-1.06.439H3.5A1.5 1.5 0 0 1 2 14.5v-11zM3.5 3a.5.5 0 0 0-.5.5v11a.5.5 0 0 0 .5.5h6.086a.5.5 0 0 0 .353-.146l4.915-4.915A.5.5 0 0 0 15 9.586V3.5a.5.5 0 0 0-.5-.5h-11z"/>
                                            <path fill-rule="evenodd" d="M10.5 10a.5.5 0 0 0-.5.5v5H9v-5A1.5 1.5 0 0 1 10.5 9h5v1h-5z"/>
                                        </svg>
                                    </a>
                                    <!-- exibe boleto publicado e permite despublicar -->
                                    @if($boleto->isPublicado =='sim')
                                        <a href="{{route('admin.boleto.removePublication',['boleto'=>$boleto->id])}}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="Publicado">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                                <path fill-rule="evenodd" d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                            </svg>
                                        </a>
                                    <!-- exibe boleto despublicado e permite publicar --> 
                                    @else
                                        <a href="{{route('admin.boleto.activePublication',['boleto'=>$boleto->id])}}" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Não Publicado">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-eye-slash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.79 12.912l-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                                                <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708l-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829z"/>
                                                <path fill-rule="evenodd" d="M13.646 14.354l-12-12 .708-.708 12 12-.708.708z"/>
                                            </svg>
                                        </a> 
                                    @endif
                                    <!-- Validação se houver limite de inscritos para esse evento de boleto, se sim e já bateu exibe o alerta abaixo - animação de css em appBlade-->                                   
                                    @if(isset($boleto->limQtdeInscritos) && $boleto->consumidores()->count() >=  $boleto->limQtdeInscritos)                                        
                                        <a href="#" class="btn btn-sm btn-default wbmAnimation"  disabled="disabled" data-toggle="tooltip" title="Atingida a quantidade de inscrições delimitada">
                                            <svg width="1.0625em" height="1em" viewBox="0 0 17 16" class="bi bi-exclamation-triangle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M7.938 2.016a.146.146 0 0 0-.054.057L1.027 13.74a.176.176 0 0 0-.002.183c.016.03.037.05.054.06.015.01.034.017.066.017h13.713a.12.12 0 0 0 .066-.017.163.163 0 0 0 .055-.06.176.176 0 0 0-.003-.183L8.12 2.073a.146.146 0 0 0-.054-.057A.13.13 0 0 0 8.002 2a.13.13 0 0 0-.064.016zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
                                                <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
                                            </svg>
                                        </a>                                                                                
                                    @endif                                      
                                </div>                                    
                            </td>                           
                        </tr>                    
                    @endforeach
                    </tbody>
                </table>
                <br>
                <div class="row mx-auto">
                    <div class="col-8"></div>
                    <div class="col-4">                        
                        <a href="{{route('admin.boleto.historicoDoGrupo',['grupoID'=>$grupoSelecionado[0]->id])}}"class="btn btn-info">Histórico de boletos deste grupo</a>
                    </div>
                                    
                </div>
                
            @else
                <h5 align="center">Selecione um grupo acima!</h5>
            @endif
        @endif 
    </div>
    
@endsection