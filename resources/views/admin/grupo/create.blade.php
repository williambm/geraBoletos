@extends('layouts.app')


@section('content')
    <div class="container">
    <h1>Criar Novo Grupo</h1>
    <form action="{{route('admin.grupo.store')}}" method="post">
        @csrf

        <div class="form-group">
            <label>Nome do Grupo</label>
            <input type="text" name="nome" id="nome" class="form-control" value="">

            <!-- @error('name')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror -->
        </div>

        <div class="form-group">
            <label>Descrição</label>
            <input type="text" name="descricao" id="descricao" class="form-control" value="">

            <!-- @error('description')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror -->
        </div>        
       
        <div>
            <button type="submit" class="btn btn-lg btn-success">Criar Grupo</button>
        </div>
        {{csrf_field()}}
    </form>
    </div>    
@endsection