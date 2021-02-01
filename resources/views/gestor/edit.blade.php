@extends('layouts.app')


@section('content')
    <div class="container">
    <h1>Editar Gestor</h1>
    <form action="{{route('gestor.update',['gestor'=>$pessoaID])}}" method="post">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Número USP</label>
            <input type="number" name="codPes" id="codPes" class="form-control" value="{{$pessoa->codPes}}">

            <!-- @error('name')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror -->
        </div>

        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{$pessoa->nome}}">

            <!-- @error('description')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror -->
        </div>        
       
        <div>
            <button type="submit" class="btn btn-lg btn-success">Atualizar</button>
        </div>
        {{csrf_field()}}
    </form>
    </div>

    
@endsection