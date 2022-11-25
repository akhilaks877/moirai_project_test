@extends('layouts.app')

@section('content')
 <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header d-flex" id="app">
     <div class="col-md-6 mx-auto mt-3" style="max-width: 400px;">
         <div class="row">
             <div class="col-md-12">
                 <div class="mb-3 text-center">
                     <img src="../assets/images/logo-inverse.png" title="Moiraï Publishing logo" alt="Moiraï Publishing logo" width="300" />
                 </div>
             </div>
             <div class="col-md-12">
                 <div class="main-card mb-3 card">
                     <div class="card-body">
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif
                         <h5 class="card-title">{{ __('Reset Password') }}</h5>
                         <form method="POST" action="{{ route('password.email') }}">
                             @csrf
                             <div class="form-row">
                                 <div class="col-md-12">
                                     <div class="position-relative form-group">
                                         <label for="exampleEmail11" class="">Email</label>
                                         <input name="email"  id="exampleEmail11" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                     </div>
                                     @error('email')
                                     <span class="invalid-feedback" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                   @enderror
                                 </div>
                             </div>
                             <div class="form-row row mb-0">
                                 <div class="col-md-8 offset-md-4">
                                     <button type="submit" class="btn btn-primary ">Send Password Reset Link</button>
                                 </div>
                             </div>
                             <hr>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 @endsection

