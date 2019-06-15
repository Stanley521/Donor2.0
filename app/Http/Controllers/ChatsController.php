<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Message;
use App\User;
use App\Conversation;
use App\Events\MessageSent;

class ChatsController extends Controller
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
    public function index( $friend_id)
    {
        $user = Auth::user();
        $friend = User::whereId( $friend_id)->first();
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
                ->orWhereIn('user_two_id', [$friend->id, $user->id]);
            })
            ->where('status', 'connected');
        })->first();

        // echo '<pre>';
        // print_r($conversation);
        // echo '</pre>';
        // die();

        if ( $conversation->user_two_id == Auth::user()->id) {
            if( $conversation->status == 'pending') {
                $conversation->status="connected";
                $conversation->save();
            }
        }
        
        return view('chat.chat', compact('friend', 'conversation'));
    }

    /**
     * Fetch all messages
     *
     * @return Message
     */
    public function fetchMessages( $friend_id)
    {
        $user = Auth::user();
        $friend = User::whereId( $friend_id)->first();
        if (!$friend) {
            return redirect( route('chat.index'))
            ->withErrors(sprintf('Something went awfully wrong.'));
        }
        $conversation = Conversation::whereIn('user_one_id', [$friend->id, $user->id])->whereIn('user_two_id', [$friend->id, $user->id])
        ->where('status', 'connected')->first();
        
        return Message::where('conversation_id', $conversation->id)->with('user')->get();
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
     */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        // $message = $user->messages()->create([
        //     'message' => $request->input('message')
        // ]);
        $message = new Message();
        $message->message = $request->message['message'];
        $message->status = 'd';
        $message->conversation_id = $request->message['conversation_id'];
        $message->user_id = $user->id;
        $message->save();

        broadcast(new MessageSent($user, $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}
