@extends('layouts.app')

@section('content')    


<h3> Registered players to my Team : </h3>
<table>
        <thead>
            <tr>
                <th> My Team</th>
                <th> Registered Players </th>
                <th> Status </th>
            </tr>
        </thead>
        <tbody> 
             @foreach($players as $player)
              <tr>
                  <td> {{$player->teamName}}</td>
                  <td> {{$player->playerName}} </td>
                  <td> {{$player->status}} </td>
                  <td> {{ Form::open(['action' => ["PagesController@acceptTeamPlayer", $player->playerID, $player->teamID, $player->playerName], 'method' => 'POST']) }}
                            {{Form::submit('Accept', ['class'=>'btn btn-primary'])}}
                       {{ Form::close() }}
                  </td>
                   <td> {{ Form::open(['action' => ["PagesController@declineTeamPlayer", $player->playerID, $player->teamID], 'method' => 'POST']) }}
                            {{Form::submit('Decline', ['class'=>'btn btn-danger'])}}
                        {{ Form::close() }}
                   </td>
                   
              </tr>
             @endforeach
       </tbody>
</table>


@endsection
