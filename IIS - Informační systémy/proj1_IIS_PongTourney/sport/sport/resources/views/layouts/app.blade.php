<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/scrollbar.css')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=B612&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Notable&display=swap" rel="stylesheet">
    <title>{{config('app.name','WBpong')}}</title>   
    <link rel="stylesheet" href="animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
</head> 

<style>
    html{
        scroll-behavior: smooth;
    }
    body, html{
        background: url("/bg1.png") no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        background-size: cover;
        -o-background-size: cover;
        height: 100%;
        width: 100%;
    }
    .button {
        background-color: black;
        border: none;
        text-decoration: none;
        color: white;
        padding: 8px 32px;
        text-align: center;
        font-size: 21px;
        margin: auto;
        opacity: 1;
        transition: 0.2s;
        border-radius: 0;
    }
    .button:hover{
        color: white;
        background-color: #C14545;
        opacity: 0.9;
    }
    .MyHeaderLogo:hover{
        background: -webkit-linear-gradient(45deg, #EB3349,  #F45C43);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .MyDropdown:hover{
        color: #EF4446;
    }
    .MyLogin:hover{
        color: #EF4446 !important;
    } 
    .myNavbar{
        background-color:rgba(0, 0, 0, 0.5);
    }
    .MyHeaderLogo{
        text-align: left;
        color: white;
        font-family: 'Notable', sans-serif;
    }
    .dropdown-menu{
        background-color:black;
    }
    .dropdown-content {
        width: 100%;
        display: none;
        position: absolute;
        background-color: #414141;
        min-width: 100%;
        z-index: 1;
    }
    .dropdown-content a{
        width: 100%;
        color: white;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }
    .dropdown-item{
        color: white !important;
    }
    .dropdown-content a:hover {background-color: #C14545;}
    .dropdown:hover .dropdown-content {display: block;}
    .dropdown-item:hover {color: white;background-color: #C14545; opacity: 0.95;}
</style>

{{-- <div style="position:fixed; width:100%; margin-top: -12vh;"> --}}
    <nav class="navbar navbar-expand-md navbar-light shadow-sm myNavbar">
        <div class="container" style="margin:0px; min-width:100%;">
            @include('inc.messages')
            <a class="navbar-brand" href="{{ url('/') }}">
                <h3 class="MyHeaderLogo">Pong|Tourney</h3>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto"></ul>
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a style="color:white" class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a style="color:white" class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown" style="text-align:right">
                            <a id="navbarDropdown" style="color:white" class="nav-link dropdown-toggle MyLogin" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right " aria-labelledby="navbarDropdown" >
                                <a class="dropdown-item MyDropdown" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <a class="dropdown-item MyDropdown" href="/user/{{Auth::user()->id}}/edit">Edit Account</a> 

                                <a class="dropdown-item MyDropdown" href="/changePassword">Change Password</a> 

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <!-- 9776 -->
    <!-- Three buttons in a group -->
    {{-- <div class="btn-group " style="width:100%; margin: 3px 0px 3px 0px;">
        <a class="btn button " style="width:25%; color:white; margin: 0px 3px 0px 0px" href="/tournament">&#9776 Tournaments</a>
        <a class="btn button " style="width:25%; color:white; margin: 0px 3px 0px 0px" href="/team">&#9776 Teams</a>
        <a class="btn button " style="width:25%; color:white; margin: 0px 0px 0px 0px" href="/player">&#9776 Players</a>
    </div> --}}
    <div class="btn-group" style="width:100%; " >
        <div class="dropdown ButtonDropdown" style="width:100%"  >
            <a class="btn button" style="width:100%; color:white; margin: 0px 3px 0px 0px" href="/tournament">&#9776 Tournaments</a>
            <div class="dropdown-content">
                @if(!Auth::guest())
                    <a href="/tournament/create">Create Tournament</a>
                    <a href="/registeredToTour">Tournaments Created by Me</a>
                @endif   
            </div>
        </div>
        <div class="dropdown ButtonDropdown" style="width:100%"  >
            <a class="btn button" style="width:100%; height:100% ; color:white; margin: 0px 3px 0px 0px" href="/team">&#9776 Teams</a>
            <div class="dropdown-content">
                {{-- @if(!Auth::guest())
                    <a href="/team/create">Create Team</a>
                @endif --}}
            </div> 
        </div>
            <a class="btn button" style="width:100%; color:white; margin: 0px 0px 0px 0px" href="/player">&#9776 Players</a>
        </div>
    </div>
{{-- </div> --}}
    {{-- <div style="margin-top:12vh;"> --}}
        @yield('content')
    {{-- </div> --}}
</html>
