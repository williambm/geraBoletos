@extends('layouts.app')


@section('content')
<!-- 
    O formulário atualiza as informações apenas dos daos do grupo; nome e descrição
    é depois do formulário que é realizado as permissões de pessoas no grupo, novas e remover permissão..
    são métodos diferentes 
-->

    <div class="container">
        <h1>Editar Grupo</h1>
        <form action="{{route('admin.grupo.update',['grupo'=>$grupo->id])}}" method="post">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nome do Grupo</label>
                <input type="text" name="nome" id="nome" class="form-control" value="{{$grupo->nome}}">

                <!-- @error('name')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror -->
            </div>

            <div class="form-group">
                <label>Descrição</label>
                <input type="text" name="descricao" id="descricao" class="form-control" value="{{$grupo->descricao}}">

                <!-- @error('description')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror -->
            </div>       
        
            <div>
                <button type="submit" class="btn btn-lg btn-success">Atualizar Grupo</button>
            </div>
            {{csrf_field()}}
        </form>
        <br>
        <!-- Parte de permisões do grupo, valida se tem ou não membros -->        
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"  align="center">
            <h3><u>Permissões deste grupo de trabalho</u></h3>
            <br>
            <br>            
            </div>    
            <!-- Lista membros e revoga permissão -->
           @if(isset($permitidos) && $permitidos->count() > 0 )
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>Nº USP</th>
                            <th>Nome</th>       
                            <th>Ações</th>            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permitidos as $permitido)
                            <tr>            
                                <td>{{$permitido->codPes}}</td>
                                <td>{{$permitido->nome}}</td>                                              
                                <td>
                                    <div class="btn-toolbar">
                                        <form action="{{route('admin.grupo.removePermission',['grupo'=>$grupo->id , 'pessoa'=>$permitido->id])}}" method="POST">
                                            @csrf
                                            @method("PUT")
                                            <button type="submit" class="btn btn-sm btn-danger">Remover</button>
                                        </form>
                                    </div>                   
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            @else            
                <p>Sem membros no grupo</p>
            @endif
                </table>
                <br>
                 <!-- Dá permissão a pessoa no grupo -->
                <h4 class="text-center"><u>Fornecer permissão ao grupo de trabalho</u></h4>
                <div class="jumbotron jumbotron-fluid">
                <div class="container">
                
                <form class="form-inline" action="{{route('admin.grupo.addPermission',['grupo'=>$grupo->id])}}" method="post">                    
                        @csrf
                        @method("PUT")
                            <div class="form-group mr-5 ml-2">
                                <label class="mr-1">Número USP</label>
                                <input class="form-control" type="number" name="codPes" id="codPes" value="">
                            </div>
                            
                            <div class="form-group mr-5 ">
                                <label class="mr-1">Nome</label>                            
                                <input class="form-control" style="min-width:300px;" type="text" name="nomePessoa" id="nomePessoa" value="">
                            </div>

                            <div class="mx-auto">
                            <button type="submit" class="btn btn-lg btn-success" style="padding=10px">Permitir</button>
                            </div>
                        
                        {{csrf_field()}}
                </form>
                </div>
            </div>
                
        </div>
    </div>

    <!--Ajax e demais necessidades / Carregamento de Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
    <script>
        $(document).ready(function(){
            
            $('#codPes').keyup(function(){
                var query = $(this).val();
                if(query != '')
                {
                    var _token = $('input[name="_token"]').val(); //Faz o fetch dos dados no exemplo

                    $.ajax({
                        url:"{{route('admin.pessoa.liveSearch')}}",
                        method:"POST",
                        data:{query:query, _token:_token},
                        success:function(data)
                        {                            
                            $('#nomePessoa').append('#nomePessoa').val(data);                            
                        }

                    })
                }
            });
        });        
    </script>
@endsection