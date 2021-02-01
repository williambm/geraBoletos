@extends('layouts.app')


@section('content')
    <div class="container">
    <h3>Criar Novo Boleto</h3>
    <span>Você está trabalhando no grupo <b>{{$grupo[0]->nome}}</b></span>
    <hr>
    <form action="{{route('admin.boleto.update',['boleto'=>$boleto->id])}}" method="post">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-10">
                <label >Nome do Evento de Boleto <b style="color:red;">*</b></label>
                <input type="text" name="nomeEvento" id="nomeEvento" class="form-control mb-1  @error('nomeEvento') is-invalid @enderror" value="{{$boleto->nomeEvento}}" @if($hasBoletoEmitido == "true" ) readonly @endif>

                @error('nomeEvento')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-10">
                <label >Texto descritivo / Observações Legais <b style="color:red;">*</b></label>
                <textarea name="obsLegal" cols="30" rows="05" id="obsLegal" class="form-control mb-1 @error('obsLegal') is-invalid @enderror" value="{{$boleto->obsLegal}}" @if($hasBoletoEmitido == "true" ) readonly @endif>{{$boleto->obsLegal}} </textarea>

                @error('obsLegal')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="col-5">
                <label ><b>Início</b> do Período de Publicação <b style="color:red;">*</b></label>
                <input type="date" name="iniDataPublicacao" id="iniDataPublicacao" class="form-control @error('iniDataPublicacao') is-invalid @enderror" value="{{$boleto->iniDataPublicacao}}" min="{{$dataAtual ?? ''}}">

                @error('iniDataPublicacao')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
                        
            <div class="col-5">
                <label><b>Fim</b> do Período de Publicação <b style="color:red;">*</b></label>
                <input type="date" name="fimDataPublicacao" id="fimDataPublicacao" class="form-control @error('fimDataPublicacao') is-invalid @enderror" value="{{$boleto->fimDataPublicacao}}" min="{{$dataAtual ?? ''}}">

                @error('fimDataPublicacao')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-10">
                <label >Informações Boleto Sacado</label>
                <textarea type="text" cols="30" rows="05"name="infoSacado" id="infoSacado" class="form-control mb-1" value="{{$boleto->infoSacado}}" @if($hasBoletoEmitido == "true" ) readonly @endif>{{$boleto->infoSacado}}</textarea>

                <!-- @error('name')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror -->
            </div>
        </div>
        <div class="row">
            <div class="col-7">
                <label >Instrução do Objeto de Cobrança <b style="color:red;">*</b></label>
                <input type="text" name="instrObjCobranca" id="instrObjCobranca" class="form-control mb-1 @error('instrObjCobranca') is-invalid @enderror" value="{{$boleto->instrObjCobranca}}" @if($hasBoletoEmitido == "true" ) readonly @endif>

                @error('instrObjCobranca')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="col-3">
                <label>Alertar quantidade de inscritos?</label>
                <input type="text" name="limQtdeInscritos" id="limQtdeInscritos" class="form-control mb-1" value="{{$boleto->limQtdeInscritos}}">
            </div>
        </div>
        <div class="row mb-1 mt-1">
            <div class="col-5">
                <label >Estrutura Hierárquica <b style="color:red;">*</b></label>
                <input type="text" name="estrutHierarq" id="estrutHierarq" class="form-control mb-1 @error('estrutHierarq') is-invalid @enderror" value="{{$boleto->estrutHierarq}}" @if($hasBoletoEmitido == "true" ) readonly @endif>

                @error('estrutHierarq')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="col-3">
                <label >Código da Fonte de Recurso <b style="color:red;">*</b></label>
                <input type="number" name="codFonteRecurso" id="codFonteRecurso" class="form-control mb-1 @error('codFonteRecurso') is-invalid @enderror" value="{{$boleto->codFonteRecurso}}" @if($hasBoletoEmitido == "true" ) readonly @endif>

                @error('codFonteRecurso')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="col-2">
                <label >Código da Unidade<b style="color:red;">*</b></label>
                <input type="number" name="codUnidade" id="codUnidade" class="form-control mb-1 @error('codUnidade') is-invalid @enderror" value="{{$boleto->codUnidade}}" @if($hasBoletoEmitido == "true" ) readonly @endif> 

                @error('codUnidade')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            
        </div>
        <div class="form-row">
            <div class="col-3">
                <label >Nome da Fonte <b style="color:red;">*</b></label>
                <input type="text" name="nomeFonte" id="nomeFonte" class="form-control @error('nomeFonte') is-invalid @enderror" value="{{$boleto->nomeFonte}}" @if($hasBoletoEmitido == "true" ) readonly @endif>

                @error('nomeFonte')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>                        
            <div class="col-4">
                <label>Nome da Subfonte <b style="color:red;">*</b></label>
                <input type="text" name="nomeSubFonte" id="nomeSubFonte" class="form-control @error('nomeSubFonte') is-invalid @enderror" value="{{$boleto->nomeSubFonte}}" @if($hasBoletoEmitido == "true" ) readonly @endif>

                @error('nomeSubFonte')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="col-3">
                <label >Código do convênio</label>
                <input type="number" name="codConvenio" id="codConvenio" class="form-control mb-1" value="{{$boleto->codConvenio}}" @if($hasBoletoEmitido == "true" ) readonly @endif>

                <!-- @error('name')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror -->
            </div>
        </div>        
        <div class="row mb-1 mt-1">
            <div class="col-4">
                <label >Data de Vencimento do Boleto <b style="color:red;">*</b></label>
                <input type="date" name="dataVenc" id="dataVenc" class="form-control mb-1 @error('dataVenc') is-invalid @enderror" value="{{$boleto->dataVenc}}" min="{{$dataAtual ?? ''}}" >

                @error('dataVenc')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="col-3">
                <label >Valor do Documento <b style="color:red;">*</b></label>
                <input type="text" name="valor" id="valor" class="form-control mb-1 @error('valor') is-invalid @enderror" value="{{$boleto->valor}}" data-thousands="." data-decimal="," data-prefix="R$ " @if($hasBoletoEmitido == "true" ) readonly @endif>

                @error('valor')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="col-3">
                <label >Desconto</label>
                <input type="text" name="desconto" id="desconto" class="form-control mb-1" value="{{$boleto->desconto}}" data-thousands="." data-decimal="," data-prefix="R$ " @if($hasBoletoEmitido == "true" ) readonly @endif >

                <!-- @error('name')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror -->
            </div>
        </div>
        
            
       
        <div>
            <button type="submit" class="btn btn-lg btn-success">Atualizar Evento de Boleto</button>
            <a href="{{route('admin.boleto.activePublication',['boleto'=>$boleto->id])}}" class="btn btn-sm btn-warning" 
                style="
                color: #fff;
                display: inline-block;
                font-weight: 400;
                text-align: center;
                vertical-align: middle;
                user-select: none;
                padding: .5rem 1rem;
                font-size: 1.25rem;
                line-height: 1.5;
                border-radius: .3rem;
                ">
                Cancelar Edição
            </a>
            
        </div>
        {{csrf_field()}}
    </form>
    </div> 

    <!-- Área destinada a carregar scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" ></script>
    <script>

            alert("No modo de edição é removido automaticamente a publicação do link de inscrição do boleto para que se possa realizar as alterações ! A publicação será automaticamente reativada após a confirmação ou cancelamento das edições a serem realizadas !!!   Faça isso através dos botões do formulário!!")
        
            $(function() {
                $('#valor').maskMoney();
                $('#desconto').maskMoney();
            })
        
    </script>
@endsection