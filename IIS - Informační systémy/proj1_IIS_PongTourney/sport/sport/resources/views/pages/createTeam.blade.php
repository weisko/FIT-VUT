@extends('layouts.app')

@section('content')    
    <h1>Create your own Team:</h1>

    {!! Form::open(['action' => 'TeamController@store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('country', 'Country')}}
            {{Form::text('country', '', ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('logo', 'Logo')}} 
            {{Form::file('logo', $attributes = ['accept'=>"image/x-png,image/gif,image/jpeg"])}}
        </div>
        {{Form::submit('Create a Team', ['class' => 'btn btn-primary'])}}
    
    {!! Form::close() !!}
@endsection
