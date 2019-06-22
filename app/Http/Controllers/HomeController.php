<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Location;
use App\Placeopen;
use App\Placeclose;
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
//        $client = new Client(); //GuzzleHttp\Client
//        $latitude = '';
//        $longitude = '';
//        if (isset($_COOKIE['latitude']) && isset($_COOKIE['longitude'])) {
//            $latitude = $_COOKIE['latitude'];
//            $longitude = $_COOKIE['longitude'];
//        }
//
//        $locations = Location::where('type', 'like', '%PMI%')->get();
//
////        echo '<pre>';
////        foreach( $locations as $location) {
////            print_r($location->name);
////        }
////        echo '</pre>';
////        die();
//
//        return view('home', ['locations'=>$locations]);

        $client = new Client(); //GuzzleHttp\Client
        $latitude = '';
        $longitude = '';
        if (isset($_COOKIE['latitude']) && isset($_COOKIE['longitude'])) {
            $latitude = $_COOKIE['latitude'];
            $longitude = $_COOKIE['longitude'];
        }
        // $url="https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=Museum%20of%20Contemporary%20Art%20Australia&inputtype=textquery&fields=photos,formatted_address,name,rating,opening_hours,geometry&key=YOUR_API_KEY"
        
        // DETAIL URL
        //    $placeId = "ChIJDTqvL0n0aS4RqUOWz0onsjg";
        //    $fields = "formatted_phone_number,international_phone_number,opening_hours,website,address_component,adr_address,alt_id,formatted_address,geometry,icon,id,name,permanently_closed,photo,place_id,plus_code,scope,type,url,utc_offset,vicinity";
        //    $key = "AIzaSyBl-94cl8p6yU-zxAP0HWKbAjF2Lr3AIQo";
        //    $url = "https://maps.googleapis.com/maps/api/place/details/json?placeid=".$placeId."&fields=".$fields."&key=".$key;

        // NEARBY URL GOOGLE SEARCH
        // $fields = "opening_hours,formatted_phone_number,url";
        // $keyword = "palang%merah";
        // $name = "palang%merah";
        // $radius = "10000";
        // $key = "AIzaSyBl-94cl8p6yU-zxAP0HWKbAjF2Lr3AIQo";
        // $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?fields=".$fields."&keyword=".$keyword."&name=".$name."&location=".$latitude.",".$longitude."&radius=".$radius."&key=".$key;
    
        // $result = $client->get( $url, ['verify' => false]);
        // $body = json_decode($result->getBody());
        // $location = collect($body->result);

        $locations = Location::where('type', 'like', 'PMI')->get();
        // echo '<pre>';
        // foreach( $locations as $location) {
            // print_r($location);
        // }
        // echo '</pre>';
        // die();

        return view('home', ['locations'=>$locations]);
    }
}
