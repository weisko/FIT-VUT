<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tournament;
use App\Match;

class TournamentsController extends Controller
{
    /** 
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
        //return Tournament::all();
        //return view('pages/tournament');
       $tournaments = Tournament::all();
       return view('pages.tournament')->with('tournaments', $tournaments);
   }

   public function index_home()
   {
        //return Tournament::all();
        //return view('pages/tournament');
       $tournaments = Tournament::all();
       return view('pages.home')->with('tournaments', $tournaments);
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
        return view('pages.createTournament');
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
          $this->validate($request, [
               'name' => 'required',
               'type' => 'required',
               'logo' => 'image|nullable|mimes:jpeg,png,jpg,gif,svg|max:1999',
               'date' => 'required',
               'registration_fee' => 'required',
               'winning_prize' => 'required',
               'maximum_teams' => 'required',
               'place' => 'required',
               'sponsor' => 'required'
          ]);

          //$filenameToStore = "";

        // Handle file Upload
        if($request->hasFile('logo')) {
            // Get filename with extension
            $filenameWithExt = $request->file('logo')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME); // PHP
            // Get just extension
            $extension = $request->file('logo')->getClientOriginalExtension();
            // Filename to store
            $filenameToStore = $filename.'_'.time().'.'.$extension;         
            // Upload logo
            $path = $request->file('logo')->storeAs('public/logos', $filenameToStore);
        }
        else {
             $filenameToStore = 'defaultTournamentLogo.png';
        }

          // Create Tournament
          $tournament = new Tournament;
          $tournament->name = $request->input('name');
          $tournament->type = $request->input('type');
          $tournament->logo = $filenameToStore;
          $tournament->date = $request->input('date');
          $tournament->registration_fee = $request->input('registration_fee');
          $tournament->winning_prize = $request->input('winning_prize');
          $tournament->maximum_teams = $request->input('maximum_teams');
          $tournament->refferee = "Free to register";
          $tournament->place = $request->input('place');
          $tournament->sponsor = $request->input('sponsor');
          $tournament->user_id = auth()->user()->id;
          $tournament->save();

          return redirect('/tournament')->with('success', 'New tournament created');
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {

        $matches = Match::all();
        $tournament = Tournament::find($id);

        //return view('pages.showTournament')->with('tournaments', $tournament);
        return view('pages.showTournament', compact('matches', 'tournament'));
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
          $tournament = Tournament::find($id);
          return view('pages.editTournament')->with('tournaments', $tournament);
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
               'name' => 'required',
               'logo' => 'image|nullable|mimes:jpeg,png,jpg,gif,svg|max:1999',
               'date' => 'required',
               'registration_fee' => 'required',
               'winning_prize' => 'required',
               'maximum_teams' => 'required',
               'place' => 'required',
               'sponsor' => 'required'
          ]);

                    // Handle file Upload
          if($request->hasFile('logo')) {
               // Get filename with extension
               $filenameWithExt = $request->file('logo')->getClientOriginalName();
               // Get just filename
               $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME); // PHP
               // Get just extension
               $extension = $request->file('logo')->getClientOriginalExtension();
               // Filename to store
               $filenameToStore = $filename.'_'.time().'.'.$extension;         
               // Upload logo
               $path = $request->file('logo')->storeAs('public/logos', $filenameToStore);
          }
          else {
               $filenameToStore = 'defaultTournamentLogo.png';
          }

          // Update Tournament
          $tournament = Tournament::find($id);
          $tournament->name = $request->input('name');
          $tournament->logo = $filenameToStore;
          $tournament->date = $request->input('date');
          $tournament->registration_fee = $request->input('registration_fee');
          $tournament->winning_prize = $request->input('winning_prize');
          $tournament->maximum_teams = $request->input('maximum_teams');
          $tournament->place = $request->input('place');
          $tournament->sponsor = $request->input('sponsor');
          $tournament->save();

          return redirect('/tournament')->with('success', 'Tournament successfully edited');
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
        $tournament = Tournament::find($id);
        $tournament->delete();
        return redirect('/tournament')->with('success', 'Tournament Deleted');
   }
}