@extends('layouts.app')

@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-9">
            <div class="card shadow">
                <div class="__card-header"></div>

                <div class="card-body __bg-main-light text-white">
                    <h4>{{ __('Prihlásenie') }}</h4>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="op" class="col-sm-12 col-form-label text-left">{{ __('Č. občianskeho preukazu') }}</label>

                            <div class="col-sm-12">
                                <input id="op" class="form-control @error('op') is-invalid @enderror" name="op" value="{{ old('op') }}" required autocomplete="op" autofocus>

                                @error('op')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-sm-12 col-form-label text-left">{{ __('Heslo') }}</label>

                            <div class="col-sm-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Pamätať si ma') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6">
                                <button type="submit" class="btn __btn-highlight">
                                    {{ __('Prihlásiť') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
