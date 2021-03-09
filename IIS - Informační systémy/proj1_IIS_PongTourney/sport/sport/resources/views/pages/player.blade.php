@extends('layouts.app')

@section('content')    



<table>
    <thead>
        <tr>
            <th> Player</th>
            <th> Tournament </th>
        </tr>
    </thead>
    <tbody> 
        @foreach($players as $player)
        <tr>
            <td> {{$player->playerName}}</td>
            <td> {{$player->tournamentName}} </td>
            <td> {{ Form::open(['action' => ["PagesController@writePlayerStats", $player->playerName], 'method' => 'POST']) }}
                    {{Form::submit('Show stats', ['class'=>'btn btn-primary'])}}
               {{ Form::close() }}
          </td>
        </tr>
        @endforeach
    </tbody>
</table>
    

 
@endsection