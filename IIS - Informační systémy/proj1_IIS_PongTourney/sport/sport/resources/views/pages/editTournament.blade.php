@extends('layouts.app')

@section('content')    
    <h1>Edit a Tournament:</h1>
    {!! Form::open(['action' => ['TournamentsController@update', $tournaments->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $tournaments->name, ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('logo', 'Logo')}} 
            {{Form::file('logo', $attributes = ['accept'=>"image/x-png,image/gif,image/jpeg"])}}
        </div>
        <div class="form-group">
            {{Form::label('date', 'Date')}}
            {{Form::text('date', $tournaments->date, ['class' => 'form-control', 'placeholder' => 'enter here'])}} 
        </div>
        <div class="form-group">
            {{Form::label('registration_fee', 'Registration_fee')}}
            {{Form::text('registration_fee', $tournaments->registration_fee, ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('winning_prize', 'Winning_prize')}}
            {{Form::text('winning_prize', $tournaments->winning_prize, ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('maximum_teams', 'Maximum_teams')}}
            {{Form::text('maximum_teams', $tournaments->maximum_teams, ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('place', 'Place')}}
            {{Form::text('place', $tournaments->place, ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('sponsor', 'Sponsor')}}
            {{Form::text('sponsor', $tournaments->sponsor, ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Update Tournament', ['class' => 'btn btn-primary'])}}

    {!! Form::close() !!}
@endsection
