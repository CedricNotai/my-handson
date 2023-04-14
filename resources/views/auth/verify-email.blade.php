@extends('layouts.app')

@section('content')
<div id="verify-email-page" class="container">
    <h1>Vérifiez votre adresse mail</h1>
    <p>Avant de continuer, merci de consulter votre boîte mail et de cliquer sur le lien de vérification que nous vous avons envoyé.</p>

    @if (!$message)
        {{ __('Si vous n\'avez pas reçu l\'e-mail,') }}
        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn btn-link resend-link">cliquez ici pour recevoir un autre lien.</button>
        </form>
    @else
        <p>Un nouveau lien de vérification vous a été envoyé par mail.</p>
    @endif
    
    <a href="{{ url('/') }}">Retour à l'accueil</a>
</div>
@endsection
