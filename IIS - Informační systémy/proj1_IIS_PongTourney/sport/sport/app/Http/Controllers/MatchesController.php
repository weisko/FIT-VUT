<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Match;
use App\Tournament;

class MatchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $matches = Match::all();
        // return view('pages.match')->with('matches', $matches);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.createMatch');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $tournamentID)
    {
        $this->validate($request, [
            'player1' => 'required',
            'player2' => 'required',
            'player1_sets' => 'required',
            'player2_sets' => 'required',
            'stage' => 'required',
            'winner' => 'required',
            'loser' => 'required',
            'time' => 'required'
        ]);
    
        // Create Match
        $match = new Match;
        $match->tournament_id = $tournamentID;
        $match->player1 = $request->input('player1');
        $match->player2 = $request->input('player2');
        $match->player1_sets = $request->input('player1_sets');
        $match->player2_sets = $request->input('player2_sets');
        $match->stage = $request->input('stage');
        $match->winner = $request->input('winner');
        $match->loser = $request->input('loser');
        $match->time = $request->input('time');
        $match->save();
        return redirect('/tournament')->with('success', 'New match added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $match = Match::find($id);
        return view('pages.editMatch')->with('match', $match);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'player1' => 'required',
            'player2' => 'required',
            'player1_sets' => 'required',
            'player2_sets' => 'required',
            'stage' => 'required',
            'winner' => 'required',
            'loser' => 'required',
            'time' => 'required'
        ]);

        // Create Match
        $match = Match::find($id);
        $match->player1 = $request->input('player1');
        $match->player2 = $request->input('player2');
        $match->player1_sets = $request->input('player1_sets');
        $match->player2_sets = $request->input('player2_sets');
        $match->stage = $request->input('stage');
        $match->winner = $request->input('winner');
        $match->loser = $request->input('loser');
        $match->time = $request->input('time');
        $match->save();
        return redirect('/tournament')->with('success', 'Match updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
