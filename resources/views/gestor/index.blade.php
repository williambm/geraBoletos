@extends('layouts.app')
@section('content')
    <a href="{{route('gestor.create')}}" class="btn btn-lg btn-success">Adicionar Gestor</a>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>Nº USP</th>
                <th>Nome</th>                
                <th>Ações</th>            
            </tr>
        </thead>
        <tbody>
            @foreach ($gestores as $gestor)
                <tr>            
                    <td>{{$gestor->codPes}}</td>
                    <td>{{$gestor->nome}}</td>                                       
                    <td>
                        <div class="btn-toolbar">
                            <a href="{{route('gestor.edit',['gestor'=>$gestor->id])}}" class="btn btn-sm btn-primary">Editar</a>
                            <form action="{{route('gestor.removePermission',['gestor'=>$gestor->id])}}" method='POST'>
                                @csrf
                                @method("PUT")
                                <button type="submit" class="btn btn-sm btn-danger">Remover Acesso</button>
                            </form>                                                        
                        </div>                   
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- retorna os links da paginação do array -->
    {{$gestores->links()}}
@endsection