@extends('layouts.app')

@section('content')
    <h1>Edit match</h1>
    {!! Form::open(['action' => ['MatchesController@update', $match->id], 'method' => 'POST']) !!}

        <div class="form-group">
            {{Form::label('player1', 'Home player:')}}
            {{Form::text('player1', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('player1_sets', 'Number of winned sets:')}}
            {{Form::text('player1_sets', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('player2', 'Away player:')}}
            {{Form::text('player2', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('player2_sets', 'Number of winned sets:')}}
            {{Form::text('player2_sets', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}} 
        </div>
        <div class="form-group">
            {{Form::label('stage', 'Tournament Stage:')}}
            {{Form::text('stage', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('winner', 'Winner:')}}
            {{Form::text('winner', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('loser', 'Loser:')}}
            {{Form::text('loser', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('time', 'Time:')}}
            {{Form::text('time', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>

        {{Form::submit('Edit Match', ['class' => 'btn btn-primary'])}}
    
    {!! Form::close() !!}
@endsection