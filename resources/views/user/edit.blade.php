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
                    <form action="{{ route('user.edit') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('post') }}
                        <div class="card-header d-flex justify-content-between">
                            <div class="mt-auto mb-auto">User Profile</div>
                            <button class="btn btn-success" type="submit">Confirm</button>
                        </div>

                        <div class="card-body profile_form">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <label for="file_input" class="cursor-pointer">
                                        <img class="profile_pic" id="profile_img" src="{{ Storage::url($user->filename) }}">

                                        <img class="upload_icon" src="{{ Storage::url('public/files/upload.png') }}">
                                    </label>
                                    <input type="file" name="file" id="file_input">
                                </div>

                                <div class="col-md-4 mt-auto">
                                    <div>Username</div>
                                    <div class="username">
                                        <input class="form-control" name="name" type="text" placeholder="{{ $user->name }}">
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
                                    <textarea class="form-control" name="description" id="" cols="30" rows="10"
                                              @if ($user->description == null) placeholder="Fill your description here"
                                              @else placeholder="{{ $user->description }}"
                                            @endif
                                    >

                                    </textarea>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type='application/javascript'>
        window.addEventListener('load',function(){
            var fileToRead = document.getElementById("file_input");
            
            var hi = fileToRead.addEventListener('change', function(event) {
                var files = fileToRead.files;
                if (files.length) {
                    if (this.files && this.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#profile_img').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                }

            }, false);
        }); 
    </script>
@endsection
