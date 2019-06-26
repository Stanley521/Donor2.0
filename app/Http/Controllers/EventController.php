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
use App\Eventtime;

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

    public function detail($id)
    {
        $event = Event::find($id);
        $eventimes = Event::find($id)->eventtimes()->get();
        $event->eventtimes = $eventimes;
        echo json_encode($event, JSON_PRETTY_PRINT);
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

    public function create( Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'organizer' => 'required',
            'address' => 'required',
            'description' => 'required'
        ]);

        $event = Event::create([
            'name' => $request->name,
            'organizer' => $request->organizer,
            'address' => $request->address,
            'description' => $request->description,
            'created_by' => Auth::user()->id,
            'updated_by' => Auth::user()->id,
        ]);

        $dt = json_decode($request->datetime, true);

        if (!empty($dt)) {
            foreach( $dt as $datetime) {
                // date format yyyy-mm-dd
                $date = $datetime['date'];
                $day = substr($date, 8, 2);
                $month = substr($date, 5, 2);
                $year = substr($date, 0, 4);

                $open = $datetime['open_time'];
                $open_hour = substr($open, 0, 2);
                $open_minute = substr($open, 3, 4);

                $close = $datetime['close_time'];
                $close_hour = substr($close, 0, 2);
                $close_minute = substr($close, 3, 4);

                // mktime(hour, minute, second, month, day, year)
                $open_datetime = mktime( $open_hour, $open_minute, 0, $month, $day, $year);
                $close_datetime = mktime( $close_hour, $close_minute, 0, $month, $day, $year);
                $open_datetime = date('Y-m-d H:i', $open_datetime);
                $close_datetime = date('Y-m-d H:i', $close_datetime);
                $eventtime = Eventtime::create([
                    'event_id' => $event->id,
                    'open_datetime' => $open_datetime,
                    'close_datetime' => $close_datetime,
                ]);
            }
        }

        return redirect( route('event.index'))
        ->withSuccess(sprintf('Event %s has been created.', $event->name));
    }


    public function edit( Request $request) {
        // dd($request->request);
        // die();
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required',
            'datetime' => 'required',
            'organizer' => 'required',
            'address' => 'required',
            'description' => 'required'
        ]);

        $event = Event::find($request->id);
        $event->name = $request->name;
        $event->organizer = $request->organizer;
        $event->address = $request->address;
        $event->description = $request->description;
        $event->updated_by = Auth::user()->id;
        $event->save();
        
        // Clear eventtimes
        $deleted = Eventtime::where('event_id', 'like', $event->id)->delete();

        $dt = json_decode($request->datetime, true);

        if (!empty($dt)) {
            foreach( $dt as $datetime) {
                $eventtime = Eventtime::create([
                    'event_id' => $event->id,
                    'open_datetime' => $datetime['open_datetime'],
                    'close_datetime' => $datetime['close_datetime'],
                ]);
            }
        }

        return redirect( route('event.index'))
        ->withSuccess(sprintf('Event %s has been edited.', $event->name));
    }

}