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
use App\Event;

class EventController extends Controller
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
    public function index()
    {
        $events = Event::orderBy('updated_at', 'DESC')->paginate(10);

//        echo '<pre>';
//        print_r($events[0]);
//        echo '</pre>';
//        die();

        return view('event.index', compact('events'));
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
            ->whereRaw('last_donor < DATE_SUB(CURDATE(), INTERVAL 120 day)')
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

        return view('event.find', compact('users', 'search'));
    }

    public function personal( $id)
    {
        $user = User::find($id);

        $user = UserController::userInterface( $user);
        return view('event.personal', compact('user'));
    }

    public function event( Request $request)
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

        return redirect( route('event.index'))
                    ->withSuccess(sprintf('File %s has been uploaded.', $user->name));
    }

}