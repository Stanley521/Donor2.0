<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 06/11/2019
 * Time: 1:21 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Conversation;
use App\User;
use Mail;

class ConversationController
{

    public function index( Request $request) {
        $user = Auth::user();
        $convs = Conversation::where(function($query) {
            $query->where('user_two_id', Auth::user()->id)
            ->where('status', 'pending');
        })->orWhere(function($query) use ( $user) {
            $query->where(function($query) use ($user) {
                $query->whereIn('user_one_id', [$user->id])
                ->orWhereIn('user_two_id', [$user->id]);
            })
            ->where('status', 'connected');
        })->get();

        // echo '<pre>';
        // print_r($convs);
        // echo '</pre>';
        // die();
        
        foreach( $convs as $conv) {
            if ( $conv->user_two_id == Auth::user()->id) {
                $friend_id = $conv->user_one_id;
            } elseif ( $conv->user_one_id == Auth::user()->id) {
                $friend_id = $conv->user_two_id;
            } 
            $friend = User::find($friend_id);
            $conv->friend = $friend;
        }
        

        return view('chat.index', compact('convs'));
    }

    public function request( Request $request) {
        $conv = Conversation::create([
            'user_one_id' => Auth::user()->id,
            'user_two_id' => $request->to_user_id,
            'status' => 'pending',
        ]);

        $location = (object) array('location' => 'This is dummy location');
        $location->location = 'This is dummy location';
        $location->address = 'This is dummy address';
        $location->latitude = 'This is dummy address';
        $location->longitude = 'This is dummy address';
        $location->description = 'This is dummy address';
        $location->urgent = 'This is dummy address';
        $location->accessible_until = 'This is dummy address';
        $user = User::find($request->to_user_id);
        $this->sendEmailUrgent($user, $location);

        return redirect( route('donor.find'))
            ->withSuccess(sprintf('Conversation has been requested.'));
    }

    public function sendEmailUrgent( $user, $location)
    {
        $emails = array();
        $emailAddons = array(
            $user->email => $user->name
        );
        $emails = array_merge((array)$emails, (array)$emailAddons);
        print_r($emails);
        foreach($emails as $email=>$name){
            Mail::send('emails.chat_request',['name' => $name, 'email' => $email, 'location' => $location], function($m) use ( $email, $name) {
                $m->from('hello@app.com', 'Donor Darah');
                $m->to($email, $name);
                $m->subject('Nearby family need your help now!');
            });
        }
    }
}