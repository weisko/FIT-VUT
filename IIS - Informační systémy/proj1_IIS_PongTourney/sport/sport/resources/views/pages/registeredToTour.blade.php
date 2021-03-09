@extends('layouts.app')

@section('content')   


<h3> Registered players to my Tournaments : </h3>
    <table>
        <thead>
            <tr>
                <th> Tournament </th>
                <th> Type of registration </th>
                <th> Name </th>
                <th> Type of tournament</th>
                <th> Status</th>
            </tr>
        </thead>
        <tbody> 
             @foreach($players as $player)
              <tr>
                  <td> {{$player->tournamentName}}</td>
                  <td> {{$player->registrationType}}</td>
                  @if($player->registrationType == 'Refferee')
                        <td> {{$player->playerName}} </td>
                  @else
                        @if($player->tournamentType == "Individual")
                            <td> {{$player->playerName}} </td>
                        @elseif($player->tournamentType == "Teams")
                            <td> {{$player->teamName}} </td>
                        @endif
                  @endif
                  <td> {{$player->tournamentType}} </td>
                  <td> {{$player->status}} </td>
                  @if($player->registrationType == 'Refferee')

                    <td> {{ Form::open(['action' => ["PagesController@acceptRefferee", $player->playerID, $player->tournamentID], 'method' => 'POST']) }}
                                {{Form::submit('Accept', ['class'=>'btn btn-primary'])}}
                        {{ Form::close() }}
                    </td>
                  @else
                    <td> {{ Form::open(['action' => ["PagesController@acceptTourPlayer", $player->playerID, $player->tournamentID], 'method' => 'POST']) }}
                            {{Form::submit('Accept', ['class'=>'btn btn-primary'])}}
                        {{ Form::close() }}
                    </td>
                  @endif
                  <td> {{ Form::open(['action' => ["PagesController@declineTourPlayer", $player->playerID, $player->tournamentID], 'method' => 'POST']) }}
                            {{Form::submit('Decline', ['class'=>'btn btn-danger'])}}
                       {{ Form::close() }}
                  </td>
              </tr>
             @endforeach
       </tbody>
    </table>


@endsection
