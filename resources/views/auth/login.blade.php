@extends('layouts.app')

@section('content')
<div id="login-page">
    <h1>Connexion</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div >
            <label for="email" class="col-form-label">E-mail</label>

            <div>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div>
            <label for="password" class="col-form-label">Mot de passe</label>

            <div>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- <div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label" for="remember">
                    Se souvenir de moi
                </label>
            </div>
        </div> --}}

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Se connecter
            </button>

            @if (Route::has('password.request'))
                <a class="nav-link" href="{{ route('password.request') }}">
                    J'ai oublié mon mot de passe
                </a>
            @endif
        </div>

    </form>

    <a class="nav-link" href="{{ route('register') }}">Je n'ai pas encore de compte</a>
</div>
@endsection
