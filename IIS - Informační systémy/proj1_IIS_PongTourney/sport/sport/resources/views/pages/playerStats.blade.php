@extends('layouts.app')

@section('content')   

<div>Matches played: {{$matchCount}}</div>
<div>Win Count: {{$winCount}}</div>
<div>Lose Count: {{$loseCount}}</div>
<div>Won Sets: {{$wonSetCount}}</div>
@endsection
