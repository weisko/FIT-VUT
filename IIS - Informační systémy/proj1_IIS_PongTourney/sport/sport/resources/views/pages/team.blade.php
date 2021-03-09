@extends('layouts.app')

@section('content')    
<style>
  
  .MyJumbotron{
    background-color:rgba(10, 10, 10, 0.3);
    min-height: 100%;
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
    margin-left: 90px;
  }
  .list{
    margin-top: 1vw !important;
    margin-bottom: 20px;
    color:white;
    background-color: black;
    min-width: 303px;
    min-height: 413px;
    max-width: 303px;
    max-height: 413px;
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
  }
  .myButton{
    min-width: 100%;
    border: 1.5px solid #EB3349 !important;
    margin-top: 5px;
    margin-bottom: 5px;
  }
  .myEditButton{
    margin: 0;
    min-width: 50%;
    border: 1.5px solid #EB3349 !important;
  }
  
  .myDeleteButton{
    margin: 0;
    min-width: 50%;
    border: 1.5px solid #EB3349 !important;
  }
  .ListContent{
    color:white;
    background-color: black;
  }
</style>

<!-- WRITE OUT TEAMS FUNCTION-->
<div class="animated fadeIn jumbotron jumbotron-fluid HeaderJumbotron ">
  <div class="container-fluid">
      <h1 class="HeaderText">Teams:</h1>
  </div>
</div>



<div class="animated fadeIn slow jumbotron-fluid MyJumbotron">
  <div class="container-fluid">

      @if((!Auth::guest()) && Auth::user()->name != 'Administrator')
        @php $counter = 0 @endphp
        @foreach($teams as $team)
          @if($team->user_id == Auth::user()->id)
            @php $counter += 1 @endphp
          @endif
        @endforeach
        @if($counter == 0)
          <a href="/team/create" class="btn button btn-default">Create Team</a>
        @else
          <a href="/registeredToTeam" class="btn button btn-default">Registrations to my Team</a>
        @endif
        @elseif(!Auth::guest() && Auth::user()->name == 'Administrator')
          <a href="/team/create" class="btn button btn-default">Create Team</a>
          <a href="/registeredToTeam" class="btn button btn-default">Registrations to my Team</a>
      @endif

    @if(count($teams) > 0)
      <div class="d-flex flex-wrap MyFlexGrid">
        @foreach($teams as $team)
          <div class="animated fadeInUp list m-5">
            <img src="/storage/logos/{{$team->logo}}" class="card-img-top" style="display:block;">
            <h1 style="margin-left:8px; margin-bottom:0px; text-align:center;">{{$team->name}}</h1>     
            <div class="text d-flex flex-column ListContent">
              <p class="myData">Country: {{$team->country}}</p>
              <p class="myData">Captain: {{$team->player1}}</p>
              <p class="myData">Second Player: {{$team->player2}}</p>
              {{ Form::open(['action' => ["PagesController@writePlayerStats", $team->name], 'method' => 'POST']) }}
                {{Form::submit('Stats', ['class'=>'btn button myButton align-self-end'])}}
              {{ Form::close() }}
              @if(!Auth::guest())
                  @if($team->user_id != Auth::user()->id)
                    {{ Form::open(['action' => ["PagesController@registerToTeam", $team->id], 'method' => 'POST']) }}
                          {{Form::submit('Register', ['class'=>'btn button myButton align-self-end'])}}
                    {{ Form::close() }}
                  @endif
                  <div class="d-flex justify-content-between">
                    @if((Auth::user()->id == $team->user_id) || (Auth::user()->name == "Administrator"))
                      <a href="/team/{{$team->id}}/edit" class="btn button myEditButton ">Edit</a>
                      {!!Form::open(['action' => ['TeamController@destroy', $team->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                          {{Form::hidden('_method', 'DELETE')}}
                          {{Form::submit('Delete', ['class' => 'btn button myDeleteButton '])}}
                      {!!Form::close()!!}
                    @endif
                  </div>
              @endif
              <p>Team created at {{$team->created_at}}</p>
            </div>
          </div> 
        @endforeach
      {{$teams->links()}}
      </div>
    @else
      <p>No teams found</p>
    @endif
  </div>
</div>


@endsection
