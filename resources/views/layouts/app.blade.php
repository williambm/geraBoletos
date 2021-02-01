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

    <title>Sistema Gera Boletos - EEUSP</title>

    <style>
    .wbmAnimation {    
    background-color: red;
    animation-name: atencao;
    animation-duration: 3s;
    }

    @keyframes atencao {
    0%   {background-color: black;}
    25%  {background-color: red;}
    50%  {background-color: yellow;}
    100% {background-color: red;}
    }
    </style>
</head>
<body>    
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="margin-bottom: 40px;">
            {{-- logo EE --}}
            <a class="navbar-brand" >
                <img src="{{ URL::to('.\assets\logoEE.jpg') }}" width="40" height="40" alt="" loading="lazy">
            </a>
            <a class="navbar-brand" href="{{route('area')}}">Gera Boletos</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
        
            <div class="collapse navbar-collapse" id="navbarSupportedContent"> 
                <!-- Menu -->               
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item ">
                    
                    <!-- Verifica através de session se é gestor -->
                    @if(session('isGestor')=='sim')
                        <a class="nav-link" href="{{route('admin.grupo.index')}}">Gerir Grupos<span class="sr-only">(current)</span></a>                    
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="{{route('gestor.index')}}">Controle de Gestores</a>
                    @endif
                    
                </ul>

                <!-- Área de Logout -->
                <div class="my-2 my-lg-0">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('logout')}}" >Sair</a>
                            <!-- <form action="#" class="logout" method="POST" style="display: none">
                                @csrf
                            </form> -->
                        </li>
                    </ul>
                </div>               
            </div>
        </nav>
    

    <div class="container">
        @include('flash::message')
        @yield('content')
    </div>
</body>
</html>