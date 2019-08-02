<!-- resources/views/chat/index.blade.php -->

@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="h3 text-center">
                    Contact Us    
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">PMI List</div>
                    
                    @if ( empty($pmis))
                        <div class="text-center">
                            There is no available chat
                        </div>
                    @else
                        <form action="{{route('guest.help')}}">
                            <p>
                                <input type="text" class="form-control" name="search" value="{{$search}}" 
                                placeholder="Find Chat here" autocomplete=off>
                            </p>
                        </form>
                        @if ( $pmis->count() == 0)
                            <div class="text-center">
                                There is no available chat
                            </div>
                        @endif
                        @foreach ( $pmis as $pmi)
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    {{$pmi->name}}
                                    @if ( $pmi->message_count != 0)
                                    <span style="border-radius: 50%; background: red; padding: 0.5em 1em; color: white">
                                        {{$pmi->message_count}}
                                    </span>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-right">
                                    <form action="{{ route('help.chat.disconnect')}}" method="post" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        {{ method_field('post') }}
                                        <input type="hidden" name="pmi_id" value="{{$pmi->id}}">
                                        <button class="btn btn-danger mr-3" type="submit">Delete</button>
                                    </form>
                                    <a href="{{ route('help.chat.chat',  ['pmi_id' => $pmi->id])}}">
                                        <button type="submit" class="btn btn-success">Chat</button>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        {{ $pmis->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection