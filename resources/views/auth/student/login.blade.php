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
                     <div class="card-body"><h5 class="card-title">Sign in</h5>
                        @if (session('login_error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('login_error') }}
                        </div>
                        @endif
                         <form method="POST" action="{{ route('student.login.submit') }}">
                             @csrf
                             <div class="form-row">
                                 <div class="col-md-12">
                                     <div class="position-relative form-group">
                                         <label for="exampleEmail11" class="">Email</label>
                                         <input name="email"  id="exampleEmail11" placeholder="example@example.com" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                     </div>
                                     @error('email')
                                   <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                   </span>
                                    @enderror
                                 </div>
                                 <div class="col-md-12">
                                     <div class="position-relative form-group">
                                         <label for="examplePassword11" class="">Password</label>
                                         <input name="password" id="examplePassword11" placeholder="**********" type="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                                     </div>
                                     @error('password')
                                     <span class="invalid-feedback" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                      @enderror
                                 </div>
                             </div>
                             <div class="form-row">
                                 <div class="col-md-4 offset-md-8">
                                     <button type="submit" class="btn btn-primary float-right">Log in</button>
                                 </div>
                             </div>
                             <hr>
                             @if (Route::has('password.request'))
                                 <a  href="{{ route('password.request') }}">
                                     {{ __('Forgot password?') }}
                                 </a>
                             @endif
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 @endsection

