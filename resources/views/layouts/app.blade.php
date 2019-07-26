<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
<style>
.stanley-100 > div {
    width: 100%;
}
.pulse-logo {
    font-size: 60px;
    margin-left: 10%;
}
.pulse-logo img{
    width: 5em
}
.nav-item {
    white-space: nowrap;
}
a {
    color: rgb(247, 63, 82);
    text-decoration: none;
}
a:hover {
    color: rgb(147, 6, 20);
    text-decoration: none;
}
.card-header {
    font-size: 2em;
    background: none;
    border-bottom: none;
    text-align: center;
}
.modal-dialog {
    margin-top: 15%;
}
</style>
</head>
<body>

    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-between stanley-100" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->

                    <div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="navbar-brand">
                            <a href="{{ url('/') }}">Home</a>
                        </div>
                        @guest
                            <div class="nav-item">
                                <a href="{{ route('guest.about') }}" class="nav-link">About Us</a>
                            </div>
                            <div class="nav-item">
                                <a href="{{ route('guest.help') }}" class="nav-link">Help</a>
                            </div>
                        @else
                            @if( Auth::user()->user_type == 'user')
                                <div class="nav-item">
                                    <a href="{{ route('chat.index') }}" class="nav-link">Chat</a>
                                </div>
                                <div class="nav-item">
                                    <a href="{{ route('donor.find') }}" class="nav-link">Find Donors</a>
                                </div>
                                <div class="nav-item">
                                    <a href="{{ route('event.index') }}" class="nav-link">Events</a>
                                </div>
                                <div class="nav-item">
                                    <a href="{{ route('donor.stock') }}" class="nav-link">Donor Stock</a>
                                </div>
                                <div class="nav-item">
                                    <a href="{{ route('guest.about') }}" class="nav-link">About Us</a>
                                </div>
                                <div class="nav-item">
                                    <a href="{{ route('guest.help') }}" class="nav-link">Help</a>
                                </div class="nav-item">
                            @endif
                            @if( Auth::user()->user_type == 'pmi')
                                <div class="nav-item">
                                    <a href="{{ route('donor.index') }}" class="nav-link">Donating</a>
                                </div>
                                <div class="nav-item">
                                    <a href="{{ route('donor.stock') }}" class="nav-link">Donor Stock</a>
                                </div>
                                <div class="nav-item">
                                    <a href="{{ route('event.index') }}" class="nav-link">Events</a>
                                </div>
                            @endif
                        @endguest
                    </div>
                    <div class="d-flex justify-content-end">
                        <!-- Authentication Links -->
                        @guest
                            <div class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </div>
                            @if (Route::has('register'))
                                <div class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </div>
                            @endif
                        @else
                            <div class="nav-item dropdown">
                                    <div id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </div>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('user.profile') }}">
                                            {{ __('Profile') }}
                                        </a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>
        
        <div class="d-flex justify-content-start pulse-logo">
            <img src="{{ asset('images/logo.png') }}" alt="">
        </div>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(setPosition);
        } else { 
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function setPosition(position) {
        document.cookie="latitude=" + position.coords.latitude;
        document.cookie="longitude=" + position.coords.longitude;
    }
    getLocation();
    </script>
    <script src="https://js.pusher.com/3.2/pusher.min.js"></script>
    
</body>
</html>
