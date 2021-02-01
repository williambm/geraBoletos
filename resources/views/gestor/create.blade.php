@extends('layouts.app')


@section('content')
    <div class="container">
    <h1>Permitir Novo Gestor</h1>
    <form action="{{route('gestor.store')}}" method="post">
        @csrf

        <div class="form-group">
            <label>Número USP</label>
            <input type="number" name="codPes" id="codPes" class="form-control" value="">

            <!-- @error('name')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror -->
        </div>

        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" value="">

            <!-- @error('description')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror -->
        </div>        
       
        <div>
            <button type="submit" class="btn btn-lg btn-success">Permitir</button>
        </div>
        {{csrf_field()}}
    </form>
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
                        url:"{{route('gestor.liveSearch')}}",
                        method:"POST",
                        data:{query:query, _token:_token},
                        success:function(data)
                        {                            
                            $('#nome').append('#nome').val(data);                            
                        }

                    })
                }
            });
        });        
    </script>
@endsection