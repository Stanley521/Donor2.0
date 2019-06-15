<!-- resources/views/chat/index.blade.php -->

@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Chats List</div>

                    @foreach ( $convs as $conv)
                        <div class="card-body d-flex justify-content-between">
                            <div>
                                @if( $conv->status == 'pending')
                                    (Pending)
                                @elseif( $conv->status == 'connected')
                                @endif
                                {{$conv->friend->name}}
                            </div>
                            <div>
                                <a href="{{ route('chat.chat',  ['friend_id' => $conv->friend->id])}}">
                                    @if ( $conv->status == 'connected')
                                        <button type="submit" class="btn btn-success">Chat</button>
                                    @elseif ($conv->status == 'pending')
                                        <button type="submit" class="btn btn-warning">Accept</button>
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection