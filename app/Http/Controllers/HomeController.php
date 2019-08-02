<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Location;
use App\Stock;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $client = new Client(); //GuzzleHttp\Client
        $latitude = '';
        $longitude = '';
        if (isset($_COOKIE['latitude']) && isset($_COOKIE['longitude'])) {
            $latitude = $_COOKIE['latitude'];
            $longitude = $_COOKIE['longitude'];
        }

        $locations = Location::where('type', 'like', 'PMI')->get();
        $stocks = Stock::orderBy('amount', 'DESC')->paginate(15);
        
        if ($stocks[0]->amount != 0) {
            $full = $stocks[0]->amount;
            foreach( $stocks as $stock) {
                if ($stock->amount != 0) {
                    $stock->percent = round( 100 * $stock->amount / $full);
                } else { 
                    $stock->percent = '0';
                }
            }
        } else {
            foreach( $stocks as $stock) {
                $stock->percent = '0';
            }
        }
        // echo '<pre>';
        // foreach( $locations as $location) {
            // print_r($location);
        // }
        // echo '</pre>';
        // die();

        return view('home', compact('locations', 'stocks'));
    }
}
