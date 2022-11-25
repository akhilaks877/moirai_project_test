@extends('layouts.app')

@section('content')
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header d-flex">
    <div class="col-md-6 mx-auto mt-3" style="max-width: 400px;">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3 text-center">
                    <img src="../assets/images/logo-inverse.png" title="Moiraï Publishing logo" alt="Moiraï Publishing logo" width="300" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-body"><h5 class="card-title">{{ __('Verify Your Email Address') }}</h5>
                        @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                         @endif
                        <div class="form-row">
                            <div class="col-md-12">
                                <p> {{ __('Before proceeding, please check your email for a verification link.') }}
                                    {{ __('If you did not receive the email') }},</p>
                            </div>
                        </div>
                        <hr>
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
