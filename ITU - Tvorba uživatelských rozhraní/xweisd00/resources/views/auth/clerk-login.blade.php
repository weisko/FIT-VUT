@extends('layouts.app')

@section('content')
<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-9">
            <div class="card shadow">
                {{-- <div class="card-heading">ADMIN Login</div> --}}
                <div class="card-body">
                    <h4>{{ __('Prihlásenie pre úradníkov') }}</h4>
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('clerk.login.submit') }}">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="email" class="col-sm-12 col-form-label text-left">{{ __('Email') }}</label>

                            <div class="col-sm-12">
                                <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
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
