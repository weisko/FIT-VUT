<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Vehicle;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Gets user data about his vehicles and violations
     * 
     * @return $data
     */
    public function getData() {
        $vehicles = Auth::user()->vehicles()->paginate(2, ['*'], 'vozidla');
        $violations = Auth::user()->violations;
        $data = [
            'vehicles' => $vehicles,
            'violations' => $violations,
        ];

        return $data;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = $this->getData();
        
        // dd($data);
        // $vehicle = Vehicle::find($data['violations'])

        for( $i = 0; $i < count($data['violations']); $i++ ) {
            $data['violations'][$i]['vehicle_name'] = Vehicle::find($data['violations'][$i]->vehicle_id)->plate;
        }

        // dd($data['vehicles'][1]->vignettes);

        return view('home')->with('data', $data);
    }

    /**
     * create new vehicle record
     * 
     * @return void
     */

    public function newregistration(Request $request) {
        // Create Vehicle
        $vehicle = new Vehicle;
        $vehicle->plate = $request->input('plate');
        $vehicle->registration_number = $request->input('registration_number');
        $vehicle->stk = $request->input('stk');
        $vehicle->ek = $request->input('ek');
        $vehicle->registered = 'pending';
        $vehicle->user_id = Auth::user()->id;
        $vehicle->save();

        return redirect('home')->with('success', 'Nová evidencia bola zaznamenaná, počkajte na potvrdenie vozidla.');
    }
}
