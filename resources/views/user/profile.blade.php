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
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="card-header d-flex justify-content-between">
                        <div class="mt-auto mb-auto">User Profile</div>
                        <a href="{{route('user.editing')}}">
                            <button class="btn btn-info">Edit</button>
                        </a>
                    </div>


                    <div class="card-body profile_form">
                        <div class="row">
                            <div class="col-md-3 text-center" >
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
                            @if( $user->user_type != 'pmi')
                            <div class="col-md-3 m-auto">
                                <div>
                                    Blood type
                                </div>
                                <div class="bloodtype">
                                    {{ $user->blood_type_rhesus}}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @if( $user->user_type != 'pmi')
                    <div class="card-body profile_mid">
                        <div class="d-flex justify-content-end">
                            <div class="col-md-4"> Last Donor: {{ $user->last_donor }}</div>
                            <div class="col-md-4"> Donor Eligibility: {{ $user->donor_eligibility }}</div>
                        </div>
                    </div>
                    @endif
                    <div class="card-body row">
                        <div class="col-md-12">

                            <div> Email : {{ $user->email }}</div>

                            <div>
                                Description :
                                <div>
                                    {{ $user->description }}
                                </div>
                            </div>
                            @if( $user->user_type != 'pmi')
                            <div>
                                PMI notes :
                                <div>
                                    {{ $user->notes }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="reminder" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Notification</h4>
                </div>
                <div class="modal-body">
                    <p>Hi {{ $user->name }}.</p>
                    <p>It is been more than 4 months since your last donor,
                        <br>
                        maybe now is a good time to go back and donors!
                    </p>
                    <p>
                        <img class="donate_pic" src="{{ Storage::url('files/donate.gif') }}">
                    </p>

                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                </div>
            </div>

        </div>
    </div>
    @if( $user->notified == 'false' && $user->user_type != 'pmi')
        <script type='application/javascript'>
            $(document).ready(function(){
                //your stuff
                $('#reminder').modal('show');
            });
        </script>
    @endif
@endsection