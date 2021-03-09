@extends('layouts.app')

@section('content')    
    <h1>Create a new Tournament:</h1>
    {!! Form::open(['action' => 'TournamentsController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('type', 'Type')}}
            {{Form::select('type', array('Individual' => 'Individual', 'Teams' => 'Teams'), 'Individual')}}
        </div>
        <div class="form-group">
            {{Form::label('logo', 'Logo')}} 
            {{Form::file('logo', $attributes = ['accept'=>"image/x-png,image/gif,image/jpeg"])}}
        </div>
        <div class="form-group">
            {{Form::label('date', 'Date')}}
            {{Form::text('date', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('registration_fee', 'Registration_fee')}}
            {{Form::text('registration_fee', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('winning_prize', 'Winning_prize')}}
            {{Form::text('winning_prize', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('maximum_teams', 'Maximum_teams')}}
            {{Form::text('maximum_teams', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('place', 'Place')}}
            {{Form::text('place', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('sponsor', 'Sponsor')}}
            {{Form::text('sponsor', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>

        {{Form::submit('Create a Tournament', ['class' => 'btn btn-primary'])}}

    {!! Form::close() !!}
@endsection
