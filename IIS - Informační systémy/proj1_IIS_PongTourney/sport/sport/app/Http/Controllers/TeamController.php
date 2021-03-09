<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Team;
use Auth;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::orderBy('name','asc')->paginate(3);
        return view('pages.team')->with('teams', $teams);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.createTeam');
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
            'country' => 'required',
            'logo' => 'image|nullable|mimes:jpeg,png,jpg,gif,svg|max:1999'
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

        // Create Team
        $team = new Team;
        $team->name = $request->input('name');
        $team->country = $request->input('country');
        $team->logo = $filenameToStore;
        $team->player1 = Auth::user()->name;
        $team->player2 = "Free to register";
        $team->user_id = auth()->user()->id;
        $team->save();

        return redirect('/team')->with('success', 'New team created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $team = Team::find($id);
        return view('pages.showTeam')->with('team', $team);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $team = Team::find($id);
        return view('pages.editTeam')->with('team', $team);
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
            'country' => 'required',
            'logo' => 'image|nullable|mimes:jpeg,png,jpg,gif,svg|max:1999'
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

        // Edit Team
        $team = Team::find($id);
        $team->name = $request->input('name');
        $team->country = $request->input('country');
        $team->logo = $filenameToStore;
        $team->save();

        return redirect('/team')->with('success', 'Team updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team = Team::find($id);
        $team->delete();
        return redirect('/team')->with('success', 'Team Deleted');
    }
}
