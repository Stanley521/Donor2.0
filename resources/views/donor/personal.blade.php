@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{ route('donor.donor') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('post') }}
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <div class="card-header">User Profile</div>

                        <div class="card-body profile_form">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <label for="file_input" class="cursor-pointer">
                                        <img class="profile_pic" src="{{ Storage::url($user->filename) }}">
                                    </label>
                                </div>

                                <div class="col-md-4 mt-auto">
                                    <div>Username</div>
                                    <div class="username">
                                        {{ $user->name }}
                                    </div>
                                </div>
                                <div class="col-md-2">
                                </div>
                                <div class="col-md-3 m-auto">
                                    <div>
                                        Blood type
                                    </div>
                                    <div class="bloodtype">
                                        {{ $user->blood_type_rhesus}}
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <select class="form-control" id="blood_type" name="blood_type">
                                            <option value="AB" @if ( $user->blood_type == 'AB') selected @endif>AB</option>
                                            <option value="A" @if ( $user->blood_type == 'A') selected @endif>A</option>
                                            <option value="B" @if ( $user->blood_type == 'B') selected @endif>B</option>
                                            <option value="O" @if ( $user->blood_type == 'O') selected @endif>O</option>
                                            <option @if ( $user->blood_type == null) selected @endif disabled>-</option>
                                        </select>
                                        <select class="form-control" id="rhesus" name="rhesus">
                                            <option value="+" @if ( $user->rhesus == '+') selected @endif>+</option>
                                            <option value="-" @if ( $user->rhesus == '-') selected @endif>-</option>
                                            <option @if ( $user->rhesus == null) selected @endif disabled>-</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body profile_mid">
                            <div class="d-flex justify-content-end">
                                <div class="col-md-4"> Last Donor: {{ $user->last_donor }}</div>
                                <div class="col-md-4"> Donor Eligibility: {{ $user->donor_eligibility }}</div>
                            </div>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-6">
                                {{--@foreach( )--}}
                                hi
                            </div>
                            <div class="col-md-6">

                                <div> Email : {{ $user->email }}</div>

                                <div>
                                    Desctription :
                                    <div>
                                        {{ $user->description }}
                                    </div>
                                </div>

                                <div>
                                    PMI notes :
                                    <textarea class="form-control" name="notes" id="" cols="30" rows="10"
                                              @if ($user->notes == null) placeholder="Fill user notes here"
                                              @else placeholder="{{ $user->notes }}"
                                              @endif
                                    >

                                    </textarea>
                                </div>
                            </div>
                            <button type="submit">Donor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
