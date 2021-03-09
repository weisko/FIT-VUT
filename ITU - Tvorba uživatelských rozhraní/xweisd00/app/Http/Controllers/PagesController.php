<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Violation;
use App\Vehicle;
use App\Vignette;
use Carbon;

class PagesController extends Controller
{
    /**
     * Gets user data about his vehicles and violations
     * 
     * @return $data
     */
    public function getData() {
        $vehicles = Auth::user()->vehicles;
        $violations = Auth::user()->violations;
        $data = [
            'vehicles' => $vehicles,
            'violations' => $violations,
        ];

        return $data;
    }

    /**
     * Gets dahsboard with users vehicles and violations
     * 
     * @return view
     */
    public function index() {
        if (Auth::check()) {
            $data = $this->getData();
            return view('index')->with('data', $data);
        } else {
            return view('index');
        }
    }
    
    /**
     * Process of form values and returning violations to be displayed
     * 
     * @return view
     */
    public function payviolation(Request $request) {
        $rules = [
            'violations_to_pay' => 'required',
        ];
    
        $customMessages = [
            'required' => 'Nebol vybraný žiaden priestupok.'
        ];

        $this->validate($request, $rules, $customMessages);

        $paymentData = Array();
        foreach ($request->input('violations_to_pay') as $v) {
            array_push($paymentData, Violation::find($v));
        };

        $data = $this->getData();

        return view('payment')->with('paymentData', $paymentData)->with('data', $data);
    }

    /**
     * Process of form values and returning vignettes to be displayed
     * 
     * @return view
     */
    public function buyvignette(Request $request) {
        $rules = [
            'vignettes_to_buy' => 'required',
        ];
    
        $customMessages = [
            'required' => 'Nebolo vybrané žiadne vozidlo.'
        ];

        $this->validate($request, $rules, $customMessages);

        $paymentData = Array();
        foreach ($request->input('vignettes_to_buy') as $v) {
            array_push($paymentData, Vehicle::find($v));
        };

        $data = $this->getData();

        return view('vig')->with('paymentData', $paymentData)->with('data', $data);
    }

    /**
     * "Paying" for violations
     * 
     * @return redirect to home
     */
    public function payForViolations(Request $request) {
        // dd($request->input('to_pay'));
        foreach ($request->input('to_pay') as $key => $id) {
            $v = Violation::find($id);
            $v->delete();
        }

        return redirect('/home')->with('success', 'Priestupky sú zaplatené.');
    }

    /**
     * "Paying" for vignettes
     * 
     * @return redirect to home
     */
    public function payForVignettes(Request $request) {
        // dd($request->all());
        for ($i = 0; $i < count($request->input('to_buy')) - 2; $i += 3) {
            $days = explode(" ", $request->input('to_buy')[$i + 1])[0];
            $days = intval($days);
            if ($days == 1) {
                $days = 365;
            }
            $date = new Carbon\Carbon($request->input('to_buy')[$i + 2]);
            // dd($date);
            // Create Vignette
            $vignette = new Vignette;
            $vignette->valid_since = new Carbon\Carbon($request->input('to_buy')[$i + 2]);
            $vignette->valid_until = $date->addDays($days);
            $vignette->vehicle_id = $request->input('to_buy')[$i];
            $vignette->save();
        }

        return redirect('/home')->with('success', 'Známky sú zakúpené.');
    }


}
