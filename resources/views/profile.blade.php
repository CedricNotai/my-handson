@extends('layouts.app')

@section('content')
<div id='profile-page'>
@if (session('success'))
    
    
    <div id="alert-success" class="alert alert-success">
        {{ session('success') }}
    </div>
    
    {{-- <script>
        setTimeout(function() {
            let success = document.getElementById("alert-success");
            if (success) {
                success.style.display = "none";
            }
        }, 5000); // 5 secondes
    </script> --}}
@endif
<form 
        id="formAccountSettings" 
        method="POST" 
        action="{{ route('profile.update',auth()->id()) }}" 
        enctype="multipart/form-data"
        class="needs-validation" 
        role="form"
        novalidate
    >
    @csrf
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="name" class="form-label">{{ trans('Nom')}}</label>
                            <input class="form-control" type="text" id="name" name="name" value="{{ auth()->user()->name }}" autofocus="" placeholder="John Doe" required @error('name') is-invalid @enderror>
                            <div class="invalid-tooltip">{{ trans('required')}}</div>
                        </div>
                        
                        <div class="mb-3 col-md-6">
                            <label for="email" class="form-label">{{ trans('Email')}}</label>
                            <input class="form-control" type="text" id="email" name="email" value="{{ auth()->user()->email }}" placeholder="john.doe@example.com">
                            <div class="invalid-tooltip">{{ trans('required')}}</div>
                        </div>
    
                        <div class="mt-2">
                            <button type="submit" class="btn btn-success">{{ trans('Enregistrer')}}</button>
                        </div>
                    </div>
                </div>
            </div>   
        </div> 
    </div>  
</form>

<form 
        id="formAccountSettings" 
        method="POST" 
        action="{{ route('profile.update-password',auth()->id()) }}" 
        enctype="multipart/form-data"
        class="needs-validation" 
        role="form"
        novalidate
    >
    @csrf
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label for="password" class="col-form-label">{{ __('Nouveau mot de passe') }}</label>
                                <div class="mb-3 col-md-9">
                                <input id="password" type="password" class="form-control  @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2 col-md-6">
                            <label for="password_confirmation" class="col-form-label">{{ __('Confirmation du mot de passe') }}</label>
                            <div class="col-md-9">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-success">{{ trans('Sauvegarder le nouveau mot de passe')}}</button>
                        </div>
                    </div>
                </div>
            </div>   
        </div> 
    </div>  
</form>

<div class="row">
    <button
        class="btn btn-success disconnect"
        onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
     {{ __('Se déconnecter') }}
    </button>
</div>
</div>

@endsection