@extends('layouts.app')

@section('content')    

    <a href="/tournament" class="btn btn-default">Go Back</a>
    <h1>{{$tournament->name}}</h1>
    <img src="/storage/logos/{{$tournament->logo}}" class="card-img-top" style="display:block; width:10%;">
    <h3>Type: {{$tournament->type}}</h3>
    <h3>Date: {{$tournament->date}}</h3>
    <h3>Registration fee: {{$tournament->registration_fee}}</h3>
    <h3>Winning prize: {{$tournament->winning_prize}}</h3>
    <h3>Maximum teams: {{$tournament->maximum_teams}}</h3>
    <h3>Refferee: {{$tournament->refferee}}</h3>
    <h3>Place: {{$tournament->place}}</h3>
    <h3>Sponsor: {{$tournament->sponsor}}</h3>
    <h6>Created at: {{$tournament->created_at}}</h6>

    @if(!Auth::guest())
        @if(Auth::user()->id != $tournament->user_id)
            @if($tournament->type == "Individual")
                {{ Form::open(['action' => ["PagesController@registerToSingleTournament", $tournament->id], 'method' => 'POST']) }}
                    {{Form::submit('Register as a single player', ['class'=>'btn btn-primary'])}}
                {{ Form::close() }}
            @endif

            @if($tournament->type == "Teams")
                    {{ Form::open(['action' => ["PagesController@registerToDoubleTournament", $tournament->id], 'method' => 'POST']) }}
                        {{Form::submit('Register your team', ['class'=>'btn btn-primary'])}}
                    {{ Form::close() }}
            @endif

            {{ Form::open(['action' => ["PagesController@registerRefferee", $tournament->id], 'method' => 'POST']) }}
                {{Form::submit('Register as a refferee', ['class'=>'btn btn-primary'])}}
            {{ Form::close() }}

        @endif
    @endif
    @if(!Auth::guest())
        @if((Auth::user()->name == "Administrator") || (Auth::user()->id == $tournament->user_id))
            <a href="/tournament/{{$tournament->id}}/edit" class="btn btn-default">Edit Tournament</a>
                {!!Form::open(['action' => ['TournamentsController@destroy', $tournament->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                    {{Form::hidden('_method', 'DELETE')}}
                    {{Form::submit('Delete Tournament', ['class' => 'btn btn-danger'])}}
                {!!Form::close()!!}
        @endif
    @endif
    
    @if(!Auth::guest())
        @if(((Auth::user()->role == 'refferee') && ($tournament->refferee_id == Auth::user()->id)) || (Auth::user()->name == "Administrator"))
            <a href="{{route('create.match', ['id' => $tournament->id])}}" class="btn btn-default">Add a match</a>
        @endif
    @endif
    
    <h1>Results of matches</h1>
    <table>
            <thead>
                <tr>
                    <th> Home Player</th>
                    <th> Winned sets </th>
                    <th> Away Player </th>
                    <th> Winned sets </th>
                    <th> Stage of Tournament </th>
                    <th> Winner </th>
                    <th> Loser </th>
                    <th> Time </th>
                </tr>
            </thead>
            <tbody> 
                @if(count($matches) > 0)
                    @foreach($matches as $match)
                        @if($match->tournament_id == $tournament->id)
                            <tr>
                                <td> {{$match->player1}}</td>
                                <td> {{$match->player1_sets}} </td>
                                <td> {{$match->player2}} </td>
                                <td> {{$match->player2_sets}} </td>
                                <td> {{$match->stage}} </td>
                                <td> {{$match->winner}} </td>
                                <td> {{$match->loser}} </td>
                                <td> {{$match->time}} </td>
                                @if(!Auth::guest())
                                    @if((($tournament->refferee_id == Auth::user()->id) && (Auth::user()->role == 'refferee')) || (Auth::user()->name == "Administrator"))
                                        <td> <a href="/match/{{$match->id}}/edit" class="btn btn-default"> Edit Match</a></td>
                                    @endif
                                @endif
                            </tr>
                        @endif
                    @endforeach
                @else <p>There are no matches</p> @endif
            </tbody>
        </table>
        
@endsection