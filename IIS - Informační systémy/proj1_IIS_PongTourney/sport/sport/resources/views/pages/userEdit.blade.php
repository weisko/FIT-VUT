@extends('layouts.app')

@section('content')
    <h1>Edit your account</h1>
    {!! Form::open(['action' => ['UsersController@update', $user->id], 'method' => 'POST']) !!}

        <div class="form-group">
            {{Form::label('name', 'Username:')}}
            {{Form::text('name', $user->name, ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        <div class="form-group">
            {{Form::label('email', 'E-mail:')}}
            {{Form::text('email', $user->email, ['class' => 'form-control', 'placeholder' => 'enter here'])}}
        </div>
        {{Form::submit('Edit your Account', ['class' => 'btn btn-primary'])}}
    
    {!! Form::close() !!}
@endsection