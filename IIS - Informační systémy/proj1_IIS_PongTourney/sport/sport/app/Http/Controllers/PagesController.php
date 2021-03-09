<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use App\Tournament;
use App\User;
use Auth;
use DB;
use Redirect;


class PagesController extends Controller
{

    // public function editUser() {

    // }

    public function registerToSingleTournament($id) {
        $countM = DB::table('team_regs')
            ->where('user_id', '=', Auth::user()->id)
            ->where('status', '=', "accepted")
            ->count();
        if($countM > 0) {
            return Redirect::back()->with('error', 'You can not register if you have a team');
        }
        $countC = DB::table('teams')
            ->where('user_id', '=', Auth::user()->id)
            ->count();
        if($countC > 0) {
            return Redirect::back()->with('error', 'You can not register if you have a team');
        }
        $count = DB::table('tournament_regs')
            // ->where('tournament_id', '=', $id) // User can register to more tournaments
            ->where('user_id', '=', Auth::user()->id)
            ->where('status', '=', "accepted")
            ->count();
        if($count > 0) {
            return Redirect::back()->with('error', 'You are already in another tournament.');
        }
        $countN = DB::table('tournament_regs')
            ->where('user_id', '=', Auth::user()->id)
            ->where('status', '=', "pending")
            ->where('tournament_id', '=', $id)
            ->count();
        if($countN > 0) {
            return Redirect::back()->with('error', 'You have already registered to this tournament');
        }
        else {
           
            DB::table('tournament_regs')->insert(
                ['user_id' => Auth::user()->id,
                'tournament_id' => $id,
                'registrationType' => 'Single player']
            );
            return Redirect::back()->with('success', 'Registration completed.');
        }
    }

    public function registerToDoubleTournament($id) {
        // $countM = DB::table('team_regs')
        //     ->where('user_id', '=', Auth::user()->id)
        //     ->where('status', '=', "accepted")
        //     ->count();
        // if($countM == 0) {
        //     return Redirect::back()->with('error', 'You must have a team mate to register here!');
        // }
        $countC = DB::table('teams')
            ->where('user_id', '=', Auth::user()->id)
            ->count();
        if($countC == 0) {
            return Redirect::back()->with('error', 'You must have a team to register here!');
        }
        $count = DB::table('tournament_regs')
            // ->where('tournament_id', '=', $id) // User can register to more tournaments
            ->where('user_id', '=', Auth::user()->id)
            ->where('status', '=', "accepted")
            ->count();
        if($count > 0) {
            return Redirect::back()->with('error', 'You are already in another tournament.');
        }
        $countN = DB::table('tournament_regs')
            ->where('user_id', '=', Auth::user()->id)
            ->where('status', '=', "pending")
            ->where('tournament_id', '=', $id)
            ->count();
        if($countN > 0) {
            return Redirect::back()->with('error', 'You have already registered to this tournament');
        }
        else {
            Auth::user()->isPlayer = 1;
            DB::table('tournament_regs')->insert(
                ['user_id' => Auth::user()->id,
                'tournament_id' => $id,
                'registrationType' => 'Team']
            );
            return Redirect::back()->with('success', 'Registration completed.');
        }
    }

    public function registerRefferee($tournament_id){
        $countM = DB::table('team_regs')
            ->where('user_id', '=', Auth::user()->id)
            ->where('status', '=', "accepted")
            ->count();
        if($countM > 0) {
            return Redirect::back()->with('error', 'You can not register if you have a team');
        }
        $countC = DB::table('teams')
            ->where('user_id', '=', Auth::user()->id)
            ->count();
        if($countC > 0) {
            return Redirect::back()->with('error', 'You can not register if you have a team');
        }
        $count = DB::table('tournament_regs')
            // ->where('tournament_id', '=', $id) // User can register to more tournaments
            ->where('user_id', '=', Auth::user()->id)
            ->where('status', '=', "accepted")
            ->count();
        if($count > 0) {
            return Redirect::back()->with('error', 'You are already in another tournament.');
        }
        $countN = DB::table('tournament_regs')
            ->where('user_id', '=', Auth::user()->id)
            ->where('status', '=', "pending")
            ->where('tournament_id', '=', $tournament_id)
            ->count();
        if($countN > 0) {
            return Redirect::back()->with('error', 'You have already registered to this tournament');
        }
        else {
            DB::table('tournament_regs')->insert(
                ['user_id' => Auth::user()->id,
                'tournament_id' => $tournament_id,
                'registrationType' => 'Refferee']
            );
        }    
        return Redirect::back()->with('success', 'Successfully registered as refferee');
    }

    public function registerToTeam($id) {
        $countM = DB::table('team_regs')
            ->where('user_id', '=', Auth::user()->id)
            ->where('status', '=', "accepted")
            ->count();
        if($countM > 0) {
            return Redirect::back()->with('error', 'You are already a member of a team.');
        }
        $countT = DB::table('team_regs')
            ->where('user_id', '=', Auth::user()->id)
            ->where('team_id', '=', $id)
            ->count();
        if($countT > 0) {
            return Redirect::back()->with('error', 'You have already registered to this team.');
        }
        $countC = DB::table('teams')
            ->where('user_id', '=', Auth::user()->id)
            ->count();
        if($countC > 0) {
            return Redirect::back()->with('error', 'You are a captain of another team.');
        }
        else {
            DB::table('team_regs')->insert(
                ['user_id' => Auth::user()->id,
                'team_id' => $id]
            );
            return Redirect::back()->with('success', 'Registration completed.');
        }
    }

    public function registeredToMyTournament(){
        $players = DB::table('tournament_regs')
        ->join('users', 'users.id', '=', 'tournament_regs.user_id')
        ->join('tournaments', 'tournaments.id', '=', 'tournament_regs.tournament_id')
        ->where('tournaments.user_id', '=', Auth::user()->id)

        ->leftJoin('teams', 'teams.user_id', '=', 'tournament_regs.user_id')
        //->select('users.name as playerName', 'tournaments.name as tournamentName', 'teams.name as teamName')

        ->select('users.name as playerName', 'tournaments.name as tournamentName', 'tournaments.type as tournamentType', 'tournament_regs.registrationType as registrationType', 
            'tournament_regs.status as status', 'users.id as playerID', 'tournament_regs.tournament_id as tournamentID', 'teams.name as teamName')
        ->get();
        return view ('pages.registeredToTour')->with('players', $players);
    }

    public function registeredToMyTeam() {
        $players = DB::table('team_regs')
        ->join('users', 'users.id', '=', 'team_regs.user_id')
        ->join('teams', 'teams.id', '=', 'team_regs.team_id')
        ->where('teams.user_id', '=', Auth::user()->id)
        ->select('users.name as playerName', 'teams.name as teamName', 'team_regs.status as status',
                'users.id as playerID', 'team_regs.team_id as teamID')
        ->get();
        return view ('pages.registeredToTeam')->with('players', $players);
    }

    public function writePlayers() {
        $players = DB::table('users')
        ->join('tournament_regs', 'tournament_regs.user_id', '=', 'users.id')
        ->join('tournaments', 'tournaments.id', '=', 'tournament_regs.tournament_id')
        ->where('tournament_regs.status', '=', 'accepted')
        ->where('tournaments.type', '=', 'Individual')
        ->select('users.name as playerName', 'tournaments.name as tournamentName')
        ->get();
        
        return view('pages.player')->with('players', $players);
    }

    public function writePlayerStats($playerName) {
        $winHomeCount = DB::table('matches')
        ->where('player1', '=', $playerName)
        ->where('winner', '=', $playerName)
        ->count();

        $winAwayCount = DB::table('matches')
        ->where('player2', '=', $playerName)
        ->where('winner', '=', $playerName)
        ->count();

        $loseHomeCount = DB::table('matches')
        ->where('player1', '=', $playerName)
        ->where('loser', '=', $playerName)
        ->count();

        $loseAwayCount = DB::table('matches')
        ->where('player2', '=', $playerName)
        ->where('loser', '=', $playerName)
        ->count();

        $wonHomeSetCount = DB::table('matches')
        ->where('player1', '=', $playerName)
        ->sum('player1_sets');

        $wonAwaySetCount = DB::table('matches')
        ->where('player2', '=', $playerName)
        ->sum('player2_sets');

        $winCount = $winHomeCount + $winAwayCount;
        $loseCount = $loseHomeCount + $loseAwayCount;
        $wonSetCount = $wonHomeSetCount + $wonAwaySetCount;

        $matchCount = DB::table('matches')
        ->where('player1', '=', $playerName)
        ->orwhere('player2', '=', $playerName)
        ->count();

        return view('pages.playerStats', ['winCount' => $winCount, 'loseCount' => $loseCount, 'wonSetCount' => $wonSetCount, 'matchCount' => $matchCount]);        
    }
        

    public function declineTourPlayer($playerID, $tournamentID){
        DB::table('tournament_regs')
        ->where('user_id', '=', $playerID)
        ->where('tournament_id', '=', $tournamentID)
        ->update(['status' => 'declined']);
        return Redirect::back()->with('success', 'Player declined.');
    }

    public function acceptTourPlayer($playerID, $tournamentID){

        $tournament = Tournament::find($tournamentID);

        if($tournament->type == 'Individual') {
            $count = DB::table('tournament_regs')
            ->where('registrationType', '=', 'Single Player')
            ->where('tournament_id', '=', $tournamentID)
            ->where('status', '=', 'accepted')         
            ->count();
        }
        if($tournament->type == 'Individual') {
            $count = DB::table('tournament_regs')
            ->where('registrationType', '=', 'Team')
            ->where('tournament_id', '=', $tournamentID)
            ->where('status', '=', 'accepted')         
            ->count();
        }
  
        if($count == $tournament->maximum_teams) {
            return Redirect::back()->with('error', 'You have reached the player limit');
        }
        else
        {
            DB::table('users')
            ->where('id', '=', $playerID)
            ->update(['role' => 'single_player']);

            DB::table('tournament_regs')
            ->where('user_id', '=', $playerID)
            ->where('tournament_id', '=', $tournamentID)
            ->update(['status' => 'accepted']);
            return Redirect::back()->with('success', 'Player accepted to your Tournament.');
        }
    }

    public function acceptRefferee($playerID, $tournamentID){
        $count = DB::table('tournament_regs')
            ->where('tournament_id', '=', $tournamentID)
            ->where('status', '=', 'accepted')
            ->where('registrationType', '=', 'Refferee')
            ->count();
        //$tournament = Tournament::find($tournamentID);
        if($count > 0) {
            return Redirect::back()->with('error', 'You have already accepted a refferee to this tournament');
        }
        else
        {
            DB::table('tournament_regs')
            ->where('user_id', '=', $playerID)
            ->where('tournament_id', '=', $tournamentID)
            ->update(['status' => 'accepted']);

            DB::table('users')
            ->where('id', '=', $playerID)
            ->update(['role' => 'refferee']);

            DB::table('tournaments')
            ->where('id', '=', $tournamentID)
            ->update(['refferee_id' => $playerID]);

            return Redirect::back()->with('success', 'Refferee accepted to your Tournament.');
        }
    }

    public function acceptTeamPlayer($playerID, $teamID, $playerName){
        $count = DB::table('team_regs')
            ->where('team_id', '=', $teamID)
            ->where('status', '=', 'accepted')
            ->count();
        //$tournament = Tournament::find($tournamentID);
        if($count > 0) {
            return Redirect::back()->with('error', 'You have already chosen a teammate!');
        }
        DB::table('users')
            ->where('id', '=', $playerID)
            ->update(['role' => 'team_player']);

        DB::table('team_regs')
            ->where('user_id', '=', $playerID)
            ->where('team_id', '=', $teamID)
            ->update(['status' => 'accepted']);

        DB::table('teams')
            ->where('id', '=', $teamID)
            ->update(['player2' => $playerName]);

        return Redirect::back()->with('success', 'Player accepted to your Team.');
    }

    public function declineTeamPlayer($playerID, $teamID){
        DB::table('team_regs')
            ->where('user_id', '=', $playerID)
            ->where('team_id', '=', $teamID)
            ->update(['status' => 'declined']);

        DB::table('teams')
            ->where('id', '=', $teamID)
            ->update(['player2' => 'Free to register']);

        return Redirect::back()->with('success', 'Player declined.');
    }


    public function createMatch($tournamentID) {

        $tour = Tournament::find($tournamentID);

        if($tour->type == 'Individual')
        {
            $players = DB::table('tournament_regs')
            ->join('users', 'users.id', '=', 'tournament_regs.user_id')
            ->where('tournament_regs.tournament_id', '=', $tournamentID)
            ->where('tournament_regs.status', '=', 'accepted')
            ->where('tournament_regs.registrationType', '=', 'Single player')
            ->select('users.name as playerName', 'users.id as playerID')
            ->get();
        
            foreach ($players as $player)
            {
                $items[$player->playerName] = $player->playerName;
            }
        }
        else if($tour->type == 'Teams')
        {
            $teams = DB::table('tournament_regs')
            ->join('teams', 'teams.user_id', '=', 'tournament_regs.user_id')
            ->where('tournament_regs.tournament_id', '=', $tournamentID)
            ->where('tournament_regs.status', '=', 'accepted')
            ->where('tournament_regs.registrationType', '=', 'Team')
            ->select('teams.name as teamName', 'teams.id as teamID')
            ->get();

            foreach ($teams as $team)
            {
                $items[$team->teamName] = $team->teamName;
            }
        }

        return view('pages.createMatch', compact('tournamentID', 'items'));
    }

    
    public function dashboard(){
        return view('pages.home');
    }
    
    public function team(){
        return view('pages.team');
    }

    public function user(){
        return view('pages.user');
    }

    public function tournament(){
        return view('pages.tournament');
    }

    public function login(){
        return view('pages.login');
    }                                                      

    public function edit(){
        return view('pages.userEdit');
    }

    public function player(){
        return view('pages.player');
    }
}