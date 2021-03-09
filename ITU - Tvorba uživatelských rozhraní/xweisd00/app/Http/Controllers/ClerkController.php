<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\Vehicle;
use App\Violation;
use App\User;

class ClerkController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:clerk');
    }

    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $pending = DB::table('vehicles')
                        ->where('registered', '=', 'pending')
                        ->get();

        return view('clerk')->with('pending', $pending);
    }

    public function addViolation(Request $request) {
        $rules = [
            'vehicle' => 'required',
            'date' => 'required',
            'place' => 'required',
        ];
    
        $customMessages = [
            'required' => 'Všetky polia musia byť vyplnené.'
        ];

        $this->validate($request, $rules, $customMessages);


        $vehicle_plate = $request->input('vehicle');
        $vehicle = DB::table('vehicles')
                                    ->where('plate', '=', $vehicle_plate)
                                    ->get();
        
        if ( count($vehicle) == 0 ) {
            return redirect()->back()->with('failure', 'Vozidlo nenájdené.');
        }
        
        $vehicle = $vehicle[0];
        

        $violation = new Violation;
        $violation->happened_on = $request->input('date');
        $violation->happened_at = $request->input('place');
        $violation->vehicle_id = $vehicle->id;
        $violation->user_id = $vehicle->user_id;
        $violation->save();

        $pending = DB::table('vehicles')
                        ->where('registered', '=', 'pending')
                        ->get();

        return redirect()->back()
                        ->with('success', 'Priestupok priradený.')
                        ->with('pending', $pending);
    }

    public function searchFunction(Request $request){
        // dd($request->all()); // DEBUG
        $q = $request->input( 'q' );
        $type = $request->input( 'type' );

        if ( $type == "0") {
            $vehicle = DB::table('vehicles')->where('plate','LIKE','%'.$q.'%')->get();
            $user = [];
        }
        else if ( $type == "1" ) {
            $user = DB::table('users')->where('name','LIKE','%'.$q.'%')->get();
            if ( count($user) == 0 ) {
                $user = DB::table('users')->where('surname','LIKE','%'.$q.'%')->get();
            }
            if ( count($user) == 0 && strpos($q, ' ') !== false) {
                $q = explode(' ', $q);
                if ( count($q) == 2 ) {
                    $user = DB::table('users')
                            ->where('name','LIKE','%'.$q[0].'%')
                            ->where('surname','LIKE','%'.$q[1].'%')
                            ->get();
                }
            }
            $vehicle = [];
        }

        $pending = DB::table('vehicles')
                        ->where('registered', '=', 'pending')
                        ->get();

        if ( $q == null )
            return view('clerk')->with('pending', $pending);

        if(count($user) > 0)
            return view('clerk')->with('user', $user)->with('pending', $pending);
        else if(count($vehicle) > 0)
            return view('clerk')->with('vehicle', $vehicle)->with('pending', $pending);
        else
            return view ('clerk')->with('failure','Nenašli sme nič skúste znova !')->with('pending', $pending);
    }

    function acceptRequest(Request $request) {
        $pending = Vehicle::find($request->input('p_id'));

        $pending->registered = 'registered';
        $pending->save();

        $pending = DB::table('vehicles')
                        ->where('registered', '=', 'pending')
                        ->get();
        
        return view('clerk')->with('pending', $pending);
    }

    function rejectRequest(Request $request) {
        $pending = Vehicle::find($request->input('p_id'));

        $pending->registered = "not registered";
        $pending->save();

        $pending = DB::table('vehicles')
                        ->where('registered', '=', 'pending')
                        ->get();
        
        return view('clerk')->with('pending', $pending);
    }

    function deleteRequest(Request $request) {
        $who = $request->input('who');

        if ( $who == 'citizen' ) {
            $user_to_delete = User::find($request->input('to_delete'));
            $user_to_delete->delete();
        } else if ( $who == 'vehicle' ) {
            $vehicle_to_delete = Vehicle::find($request->input('to_delete'));
            $vehicle_to_delete->delete();
        }
        
        $pending = DB::table('vehicles')
                        ->where('registered', '=', 'pending')
                        ->get();    

        return view('clerk')->with('pending', $pending);
    }
}
