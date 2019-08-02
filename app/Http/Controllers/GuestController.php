<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

use App\User;
use App\Conversation;
use App\Message;
use Auth;
use Illuminate\Http\RedirectResponse;

class GuestController extends Controller
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
    public function about()
    {

        return view('guest.about');
    }
    public function help(Request $request)
    {
        $search = '';
        if ($request->search) {
            $search = $request->search;
        }
        if (Auth::user()->user_type == 'user') {
            $convs = array();
            $pmis = User::where('user_type', 'like', 'pmi')->where('name', 'like', "%{$search}%")->paginate(5);
            
            foreach( $pmis as $pmi) {
                $conv = Conversation::where(function($query) use ( $pmi) {
                    $query->where(function($query) use ($pmi) {
                        $query->whereIn('user_one_id', [$pmi->id])
                        ->orWhereIn('user_two_id', [$pmi->id]);
                    })
                    ->where('status', 'connected');
                })->first();
                
                if (empty($conv)) {
                    $conv = Conversation::create([
                        'user_one_id' => Auth::user()->id,
                        'user_two_id' => $pmi->id,
                        'status' => 'connected',
                    ]);
                }
                array_push($convs, $conv);
            }
            
            foreach( $convs as $key=>$conv) {
                
                if ( $conv->user_two_id == Auth::user()->id) {
                    $friend_id = $conv->user_one_id;
                    $user_one = User::find($friend_id);
    
                    if ($search!='' && strpos($user_one->name, $search) === false) {
                        unset($convs[$key]);
                    } else {
                        $message = Message::where('conversation_id', $conv->id)->where('status', 'd')->count();
                        $conv->message_count = $message;
                        $friend = User::find($friend_id);
                        $conv->friend = $friend;
                    }
                } elseif ( $conv->user_one_id == Auth::user()->id) {
                    
                    $friend_id = $conv->user_two_id;
                    
                    $user_two = User::find($friend_id);
    
                    if ($search!='' && strpos($user_two->name, $search) === false) {
                        unset($convs[$key]);
                    } else {
                        $message = Message::where('conversation_id', $conv->id)->where('status', 'd')->count();
                        $conv->message_count = $message;
                        $friend = User::find($friend_id);
                        $conv->friend = $friend;
                    }
                }
            }
            

            return view('help.index', compact('pmis', 'search'));
        } else {
            return view('guest.help');
        }
    }
    public function chat($pmi_id)
    {
        $user = Auth::user();
        $friend = User::whereId( $pmi_id)->first();
        if (!$friend) {
            return redirect( route('chat.index'))
            ->withErrors(sprintf('Something went awfully wrong.'));
        }
        $conversation = Conversation::where(function($query) {
            $query->where('user_two_id', Auth::user()->id)
            ->where('status', 'pending');
        })->orWhere(function($query) use ( $user, $friend) {
            $query->where(function($query) use ($user, $friend) {
                $query->whereIn('user_one_id', [$friend->id, $user->id])
                ->WhereIn('user_two_id', [$friend->id, $user->id]);
            })
            ->where('status', 'connected');
        })->first();

        if (empty($conversation)) {
            $conv = Conversation::create([
                'user_one_id' => Auth::user()->id,
                'user_two_id' => $friend->id,
                'status' => 'connected',
            ]);
        } 
        return view('chat.chat', compact('friend', 'conversation'))
                ->withSuccess(sprintf('Conversation has been requested.'));
        // echo '<pre>';
        // print_r($conversation);
        // print_r(Auth::user()->id);
        // echo '</pre>';
        // die();

        // if ( $conversation->user_two_id == Auth::user()->id) {
        //     if( $conversation->status == 'pending') {
        //         $conversation->status="connected";
        //         $conversation->save();
                
        //     }
        // }
        
        // return view('chat.chat', compact('friend', 'conversation'));

    }
}