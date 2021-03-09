@extends('layouts.app')

@section('content')    
    
    
<style>
    .MyJumbotron{
        position: top;
        background-color:rgba(10, 10, 10, 0.3);
        min-height: 30vw;
        margin:auto;
        padding-top: 0px;
        padding-bottom: 0px;
    }
    .HeaderJumbotron{
        position: top;
        background-color:rgba(0, 0, 0, 0.5);
        color:tomato;
        margin:auto;
        padding-top: 0px;
        padding-bottom: 0px;
    }
    .HeaderText{
        padding-top: 12.5px;
        text-align: center;
        font-size: 8vw;
        margin:auto;
        background: -webkit-linear-gradient(45deg, #EB3349,  #F45C43 );
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .MyFlexGrid{
        align-content: center;
        margin-left: 90px;
        margin-right: auto;
    }
    .list{
        margin-bottom: 20px;
        background-color: white;
        min-width: 303px;
        min-height: 413px;
        max-width: 303px;
        max-height: 413px;
        box-shadow: 2px 2px 6px 0px  rgba(0,0,0,1);
    }
    .list img{
    max-width: 100%;
    }
    .text{
        background-color: white;
        padding: 0px 20px 0px;
    }
    .myData{
        margin-bottom: 0px;
    }
    .text > button{
        border: 0;
        color: white;
        padding: 10px;
        width: 100%;
        !important
    }
    .myButton{
        border: 1.5px solid #EB3349 !important;
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .ListContent{
        color:white;
        background-color: black;
    }
    
</style>
<div class="animated fadeIn jumbotron jumbotron-fluid HeaderJumbotron ">
    <div class="container-fluid">
        <h1 class="HeaderText">Tournaments:</h1>
    </div>
</div>

<div class="animated fadeIn slow jumbotron-fluid MyJumbotron">
    <div class="container-fluid">
        @if(count($tournaments) > 0)
            <div class="d-flex flex-wrap MyFlexGrid">
                @foreach ($tournaments as $tournament)
                        <div class="animated fadeInUp list m-5 ListContent">
                            <img src="/Tournament_logo.png" alt="Sample photo">
                            <h1 style="margin-left:8px; margin-bottom:0px; text-align:center;">{{$tournament->name}}</h1>
                            <div class="text d-flex flex-column ListContent">
                                @if (($tournament->type) == "Individual")
                                    <p class="myData ">Date: {{$tournament->date}} <span class="ml-3">Type: </span> <span class="mdi mdi-24px mdi-account large"></span></p>       
                                @endif
                                @if (($tournament->type) == "Teams")
                                    <p class="myData">Date: {{$tournament->date}} <span class="ml-3">Type: </span><span class="mdi mdi-24px mdi-account-multiple"></span> </p>
                                @endif
                                <p class="myData">Place: {{$tournament->place}}</p>
                                <p class="myData">Entry: {{$tournament->registration_fee}}€</p>
                                <p class="myData">Prize: {{$tournament->winning_prize}}€</p>
                                <a class="btn button myButton align-self-end" href="/tournament/{{$tournament->id}}">View</a>
                                <p class="myData" style="font-size:12px; margin-top:5px">Created at: {{$tournament->created_at}}</p>
                            </div>
                        </div>
                @endforeach
            </div>      
        @else
            <p>The are no created tournaments!</p>
        @endif
    </div>
</div>








@endsection