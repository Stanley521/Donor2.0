@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="card-header d-flex justify-content-between">
                        Donors list
                    </div>

                    <div class="card-body profile_form">
                        <form action="{{route('donor.find')}}">
                            <p>
                                <input type="text" class="form-control" name="search" value="{{$search}}" placeholder="Find donor here">
                            </p>
                        </form>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Blood type</th>
                                <th>Last donor</th>
                                <th>Availability</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ( $user->blood_type == null)
                                            -
                                        @elseif ( $user->blood_type != null && $user->rhesus != null)
                                            {{ $user->blood_type_rhesus }}
                                        @else
                                            {{ $user->blood_type}}
                                        @endif
                                    </td>
                                    <td>

                                        @if ( $user->last_donor == null)
                                            00-00-0000
                                        @else
                                            {{ date("d-m-Y",  strtotime($user->last_donor)) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ( $user->last_donor == null || strtotime($user->last_donor) <= \App\Http\Controllers\DonorController::last4monthtimestamp( strtotime($user->last_donor)))
                                            Yes
                                        @else
                                            No
                                        @endif
                                    </td>
                                    <td>
                                        @if ( $user->last_donor == null ||strtotime($user->last_donor) <= \App\Http\Controllers\DonorController::last4monthtimestamp( strtotime($user->last_donor)))
                                            @if ( !$user->status || $user->status == 'disconnected')
                                                <form action=" {{ route('conversation.request') }}" method="post" enctype="multipart/form-data">
                                                    {{ csrf_field() }}
                                                    {{ method_field('post') }}
                                                    <input type="hidden" name="to_user_id" value="{{$user->id}}">
                                                    <button class="btn btn-success form-control" type="submit">Request chat</button>
                                                </form>
                                            @else
                                                <button class="btn btn-info form-control text-capitalize" disabled>
                                                    {{ $user->status }}
                                                </button>
                                            @endif

                                        @else
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
