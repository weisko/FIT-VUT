@extends('layouts.app')

@section('content')
    <h1>Matches</h1>
    <a href="/match/create" class="btn btn-default">Add a match</a>
    @if(count($matches) > 0)
        @foreach($matches as $match)
            <div class="well">
                <h3>{{$match->player1}} VS {{$match->player2}}</h3>
            </div>
        @endforeach
    @else
        <p>There are no matches</p>
    @endif
@endsection