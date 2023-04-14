@extends('layouts.app')
@section('content')
<div id="home-page">
    <div id='presentation'>
        <h1>{{ config('app.name'); }}</h1>
        <p>Nous avons créé une application web qui permet à ses utilisateurs de trouver un endroit proche de chez eux pour recycler leurs appareils.</p>

        <div class="user-info">
            @if (!Auth::user())
                <p class="fw-bold">Pour commencer, merci de vous inscrire ou vous connecter.</p>
            @else
                <h2>Bienvenue {{ Auth::user()->name }}.</h2>
                <p>Vous êtes maintenant connecté !</p>
                <p>Pour profiter de notre application, pensez à autoriser la géolocalisation dans les paramètres de votre navigateur.</p>
                <p id="find-me">
                    @svg('geolocate')
                    Je me géolocalise
                </p>
                <p id="status"></p>
            @endif
        </div>

    </div>
    <div class="image"></div>
</div>

@endsection