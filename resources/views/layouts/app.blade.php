<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name'); }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.skypack.dev/maplibre-gl/dist/maplibre-gl.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
</head>
<body>
    <div id="app" class="box">
        <div class="app-header">
            <div id="top"></div>
            <nav class ='head' class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name'); }}
                    </a>
                    <div id="navbarSupportedContent">
                        <a class='login-mobile' href="{{ Auth::user() ? route('profile') : route('login') }}">
                            @svg('login')
                        </a>
                        <ul class="navbar-nav">
                            <!-- Authentication Links -->
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item login">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Se connecter') }}</a>
                                    </li>
                                @endif
    
                                @if (Route::has('register'))
                                    <li class="nav-item register">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('S\'inscrire') }}</a>
                                    </li>
                                @endif
                            @else
                                                          
                                <li class="nav-item dropdown">
                                    
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>
                                    
    
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        @if (Route::currentRouteName() != 'profile')
                                            <a id="profile" class="dropdown-item" href="{{ route('profile') }}" role="button" aria-labelledby="navbarDropdown"  aria-expanded="false" v-pre>
                                            Mon profil
                                            </a>
                                        @endif
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <a id="logout-form" class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                          document.getElementById('logout-form').submit();">
                                             {{ __('Se déconnecter') }}
                                         </a> 
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
            </nav>
        </div>
        <main class="app-content">
            @yield('content')
        </main>
        <div class="app-footer">
            <a href="#top" id="scroll-to-top">@svg('up-arrow')</i></a>
            <footer id="js-bottom">
                © 2023. {{ config('app.name'); }} - Équipe 4
            </footer> 
        </div>
    </div>
</body>

@yield('footer-scripts') 

</html>
