<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 06/09/2019
 * Time: 7:22 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\File;
use App\Conversation;
use App\Stock;

class DonorController extends Controller
{

    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        $search = '';
        if ($request->search) {
            $search = $request->search;
        }
        $users = User::where('user_type', 'like', 'user')->where('name', 'like', "%{$search}%")->orderBy('created_at', 'DESC')
        ->paginate(15);
        return view('donor.index', compact('users', 'search'));
    }

    public function stock()
    {
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

        return view('donor.stock', compact('stocks'));
    }

    public function addstock( Request $request) {
        $this->validate($request, [
            'id' => 'required',
            'amount' => 'required',
        ]);

        $stock = Stock::find($request->id);
        $stock->amount =  $stock->amount + $request->amount;
        $stock->updated_by =  Auth::user()->id;
        $stock->save();

        return redirect( route('donor.stock'))
                    ->withSuccess(sprintf('Donor has been added.'));
    }

    public function find( Request $request)
    {
        $search = '';
        if ($request->search) {
            $search = $request->search;
        }
//        strtotime($user->last_donor) <=
//        \App\Http\Controllers\DonorController::last4monthtimestamp( strtotime($user->last_donor)
        $users = User::where('user_type', 'like', 'user')
            ->where('id', '!=', Auth::user()->id)
            ->where('findable', 'like', true)
            ->where('name', 'like', "%{$search}%")
            ->where(function($query) {
                $query->whereRaw('last_donor < DATE_SUB(CURDATE(), INTERVAL 120 day)')
                ->orWhereNull('last_donor');
            })
            ->orderBy('updated_at', 'DESC')
            ->paginate(15);
        foreach ( $users as $user) {
            $conv = Conversation::whereIn('user_one_id', [Auth::user()->id, $user->id])
                ->whereIn('user_two_id', [Auth::user()->id, $user->id])->first();
            if ( $conv) {
                $user->status = $conv->status;
            }
            $user = UserController::userInterface( $user);
        }

        return view('donor.find', compact('users', 'search'));
    }

    public function personal( $id)
    {
        $user = User::find($id);

        $user = UserController::userInterface( $user);
        return view('donor.personal', compact('user'));
    }

    public function donor( Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
        $user = User::find($request->id);

        echo '<pre>';
//        dd($request);
        print_r( date('Y-m-d H:i:s',time()));
        echo '</pre>';
        if ( $request->blood_type != null) {
            $user->blood_type = $request->blood_type;
        }
        if ( $request->rhesus != null) {
            $user->rhesus = $request->rhesus;
        }
        if ( $request->description != null) {
            $user->description = $request->description;
        }
        $user->last_donor = date('Y-m-d H:i:s',time());
        $user->save();

        return redirect( route('donor.index'))
                    ->withSuccess(sprintf('%s data has been updated.', $user->name));
    }

    public static function last4monthtimestamp( $timestamp) {
        $today_start_ts = strtotime(date('Y-m-d', time()). ' 00:00:00');
        $today = $today_start_ts; $numberofpeople = 10;

        // echo date('Y-m-d H:i:s', $startdate) . "<br>";
        $todate = date('Y-m-d H:i:s', $today);

        $yesterday = $today - 60*60*24; // 1hari yang lalu
        $yesterdate = date('Y-m-d H:i:s', $yesterday);

        $last4months = $today - 60*60*24*30*4; // 4bulan yang lalu
        $last4monthsdate = date('Y-m-d H:i:s', $last4months);

        return $last4months;
    }

}