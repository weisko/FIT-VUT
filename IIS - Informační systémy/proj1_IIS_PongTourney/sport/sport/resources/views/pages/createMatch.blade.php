@extends('layouts.app')

@section('content')
    <h1>Add a new match</h1>
    {!! Form::open(['action' => ['MatchesController@store', $tournamentID], 'method' => 'POST']) !!}

        <div class="form-group">
            {{Form::label('player1', 'Home player / team:')}}
            {{ Form::select('player1', $items) }}
        </div>
        <div class="form-group">
            {{Form::label('player1_sets', 'Number of winned sets of Home player:')}}
            {{Form::text('player1_sets', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}          
        </div>
        <div class="form-group">
            {{Form::label('player2', 'Away player / team:')}}
            {{ Form::select('player2', $items) }}
        </div>
        <div class="form-group">
            {{Form::label('player2_sets', 'Number of winned sets of Away player:')}}
            {{Form::text('player2_sets', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}} 
        </div>
        <div class="form-group">
            {{Form::label('stage', 'Tournament Stage:')}}
            {{Form::select('stage', array('Round of 16' => 'Round of 16', 'Quarter-final' => 'Quarter-final', 
                'Semi-final' => 'Semi-final', 'Final' => 'Final'))}}
        </div>
        <div class="form-group">
            {{Form::label('winner', 'Winner:')}}
            {{ Form::select('winner', $items) }}
        </div>
        <div class="form-group">
            {{Form::label('loser', 'Loser:')}}
            {{ Form::select('loser', $items) }}
        </div>
        <div class="form-group">
            {{Form::label('time', 'Time:')}}
            {{Form::text('time', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>


        {{Form::submit('Add a Match', ['class' => 'btn btn-primary'])}}
    
    {!! Form::close() !!}
@endsection