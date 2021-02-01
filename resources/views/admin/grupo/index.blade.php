@extends('layouts.app')
@section('content')
    <a href="{{route('admin.grupo.create')}}" class="btn btn-lg btn-success">Criar Grupo</a>
    <table class='table table-striped'>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Descrição</th>       
                <th>Ações</th>            
            </tr>
        </thead>
        <tbody>
            @foreach ($grupos as $grupo)
                <tr>            
                    <td>{{$grupo->id}}</td>
                    <td>{{$grupo->nome}}</td>
                    <td>{{$grupo->descricao}}</td>                    
                    <td>
                        <div class="btn-toolbar">
                            <a href="{{route('admin.grupo.edit',['grupo'=>$grupo->id])}}" class="btn btn-sm btn-primary">Editar</a>
                        </div>                   
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- retorna os links da paginação do array -->
    {{$grupos->links()}}
@endsection