<!-- Formulário de inscrição para o candidato -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- CSS only -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

    <title>FORMULÁRIO PARA EMISSÃO DE BOLETO - EEUSP</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="margin-bottom: 40px;">
            {{-- logo EE --}}
            <a class="navbar-brand" >
                <img src="{{ URL::to('.\assets\logoEE.jpg') }}" width="80" height="80" alt="" loading="lazy">
            </a>
            <a class="navbar-brand">FORMULÁRIO PARA EMISSÃO DE BOLETO</a>            
        </nav>
    </header>
    
    @if(!isset($boleto))
        <!-- Boleto não existe -->        
        <div class="container">
            <h4>Evento de Geração de Boleto Inexistente</h4>            
        </div>
    
    @elseif($isTempoDeAbrirInscricao == false)
        <!-- Boleto fora do prazo inicial de públicação -->        
        <div class="container">
            <h5>O prazo para realizar a inscrição e gerar o boleto de pagamento para este evento ainda não entrou em vigor.</h5>
            <p>Está programado para entrar em vigor em: <b>{{$dataQueAbreInscricao}}</b></p>
        </div>
    @elseif($isTempoValido == false)
        <!-- Boleto fora do prazo final de públicação -->        
        <div class="container">
            <h4>O prazo para realizar a inscrição e gerar o boleto de pagamento para este evento já expirou.</h4>
        </div>
    @elseif($isPublicado == "nao")
        <!-- Boleto está flagado como não publicado pelo administrador -->
        <div class="container">
            <h5>No momento não é possível realizar a inscrição e gerar o respectivo boleto. Por favor, tente mais tarde!</h5>
        </div>
    @else
        <!-- Boleto ok - prazo de públicação e isPublicado = sim -->        
        <div class="container">
            {{-- Área do texto carregado pelo evento de boleto da inscrição (boletoID)--}}
            <div class="mx-auto">
                <h4>{{$boleto->nomeEvento}}</h4>
                <p>{{$boleto->obsLegal}}</p>
                <hr>
            </div>
        
            {{-- Área do formulário --}}        
            <form action="{{route('inscricao.gerarBoletoRegistrado',['boleto'=>$boleto->id])}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-2 col-lg-2 col-xl-2 col-sm-6">
                        <label >Nº USP</label>
                        <input type="number" name="codPes" id="codPes" class="form-control mb-1" value="">

                        <!-- @error('name')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror -->
                    </div> 
                    <div class="col-md-4 col-lg-4 col-xl-4 col-sm-6">
                        <label >Nome <b style="color:red;">*</b></label>
                        <input  type="text" name="nome" id="nome" class="form-control mb-1 @error('nome') is-invalid @enderror" value="">

                        @error('nome')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-2 col-lg-2 col-xl-2 col-sm-6">
                        <label >CPF <b style="color:red;">*</b></label>
                        <input type="text" name="cpf" id="cpf" class="form-control mb-1 @error('cpf') is-invalid @enderror" value="">

                        @error('cpf')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>                            
                </div>
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-xl-4 col-sm-6">
                        <label >E-Mail <b style="color:red;">*</b></label>
                        <input type="text" name="email" id="email" class="form-control mb-1 @error('email') is-invalid @enderror" value="">

                        @error('email')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div> 
                    <div class="col-md-4 col-lg-4 col-xl-4 col-sm-6">
                        <label >Telefone <b style="color:red;">*</b></label>
                        <input type="text" name="telefone" id="telefone" class="form-control mb-1 @error('telefone') is-invalid @enderror" value="">

                        @error('telefone')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div> 
                </div>            
                <div class="form-row">
                    <div class="col-md-2 col-lg-2 col-xl-2 col-sm-6">
                        <label >CEP <b style="color:red;">*</b></label>
                        <input type="text" name="cep" id="cep" class="form-control @error('cep') is-invalid @enderror" value="">

                        @error('cep')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-4 col-lg-4 col-xl-4 col-sm-6">
                        <label >Cidade <b style="color:red;">*</b></label>
                        <input type="text" name="cidade" id="cidade" class="form-control mb-1 @error('cidade') is-invalid @enderror" value="">

                        @error('cidade')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-1 col-lg-1 col-xl-1 col-sm-2">
                        <label >UF <b style="color:red;">*</b></label>
                        <input type="text" name="uf" id="uf" class="form-control mb-1 @error('uf') is-invalid @enderror" value="">

                        @error('uf')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-xl-4 col-sm-6">
                        <label>Endereço <b style="color:red;">*</b></label>
                        <input type="text" name="endereco" id="endereco" class="form-control @error('endereco') is-invalid @enderror" value="">

                        @error('endereco')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-1 col-lg-1 col-xl-1 col-sm-2">
                        <label>Nº <b style="color:red;">*</b></label>
                        <input type="text" name="numEndereco" id="numEndereco" class="form-control @error('numEndereco') is-invalid @enderror" value="">

                        @error('numEndereco')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-5 col-lg-5 col-xl-5 col-sm-6">
                        <label>Complemento </label>
                        <input type="text" name="complEndereco" id="complEndereco" class="form-control" value="">

                        <!-- @error('name')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror -->
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-5 col-lg-5 col-xl-5 col-sm-8">
                        <label>Este boleto será pago por uma Empresa / Instituição ?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipoSacado" id="tipoSacadoPF" value="PF" checked>
                            <label class="form-check-label">
                                Não
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipoSacado" id="tipoSacadoPJ" value="PJ">
                            <label class="form-check-label">
                                Sim
                            </label>
                        </div>
                    </div>
                </div>                       
                <div class="row mb-1 mt-1">
                    <div class="col-md-5 col-lg-5 col-xl-5 col-sm-6">
                        <label >Nome da Empresa / Instituição</label>
                        <input type="text" name="nomeEmpresaInstituicao" id="nomeEmpresaInstituicao" class="form-control mb-1" value="">

                        <!-- @error('name')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror -->
                    </div>
                    <div class="col-md-5 col-lg-5 col-xl-5 col-sm-6">
                        <label >CNPJ</label>
                        <input type="text" name="cnpjEmpresaInstituicao" id="cnpjEmpresaInstituicao" class="form-control mb-1" value="">

                        <!-- @error('name')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror -->
                    </div>                
                </div>
                <div>
                    <button type="submit" class="btn btn-lg btn-success">Gerar Boleto</button>
                </div>
                {{csrf_field()}}
            </form>
        </div> 

        <!-- Área destinada a carregar scripts -->    
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.min.js"></script>
        <script>
            $(document).ready(function(){
                //Mascara de CPF
                $('#cpf').inputmask("999.999.999-99");
                //Mascara de telefone
                $('#telefone').inputmask("(99)999999999");
                //Mascara de cep
                $('#cep').inputmask("99999-999");
                //Mascara de CNPJ
                $('#cnpjEmpresaInstituicao').inputmask("99.999.999/9999-99");
                
                //Ajax que autocompleta se a pessoa tem n USP
                $('#codPes').keyup(function(){
                    var query = $(this).val();
                    if(query != '')
                    {
                        var _token = $('input[name="_token"]').val(); //Faz o fetch dos dados no exemplo

                        $.ajax({
                            url:"{{route('inscricao.liveSearch')}}",
                            method:"POST",
                            data:{query:query, _token:_token},
                            dataType: 'json',                                              
                            success:function(data)
                            {   
                                console.log(data);
                                $('#nome').append('#nome').val(data.nome);
                                $('#cpf').append('#cpf').val(data.cpf);
                            }

                        })
                    }
                });            
            });
            $(function(){
                //Ajax que autocompleta CPF
                $('#cep').keyup(function(){
                    var cep = $(this).val();
                    if(cep != '')
                    {
                        var _token = $('input[name="_token"]').val(); //Faz o fetch dos dados no exemplo
                        
                        $.ajax({
                            url:"{{route('inscricao.liveSearchCEP')}}",
                            method:"POST",
                            data:{cep:cep, _token:_token},
                            dataType: 'json',                                              
                            success:function(data)
                            {   
                                console.log(data);
                                $('#cidade').append('#cidade').val(data.cidade);                            
                                $('#uf').append('#uf').val(data.uf);                            
                                $('#endereco').append('#endereco').val(data.logradouro);                            
                            }

                        })
                    }
                });
            })        
        </script>
    @endif
    
</body>
</html>