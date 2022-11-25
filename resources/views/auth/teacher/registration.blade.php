@extends('layouts.app')
@section('content')
@section('page-styles')
<style>
.form-section {display: none;}.form-section.current {display: inherit;}
</style>
@stop
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header d-flex">
    <div class="col-6 mx-auto mt-3" style="max-width: 600px;">
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3 text-center">
                    <img src="../assets/images/logo-inverse.png" title="Moiraï Publishing logo" alt="Moiraï Publishing logo" width="300" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <form class="regForm" action="" method="POST" data-parsley-validate>
                            <div class="form-section"><h5 class="card-title">Create a teacher account</h5>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <p>To confirm your Teacher status, please create your account with your professional school email address. If your school is not already registered with Moiraï Publishing, you can contact us by <a href="#">clicking here</a>.</p>
                                </div>
                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="lang" class="">Language</label>
                                        <select name="language" id="lang" class="form-control" data-parsley-required data-parsley-error-message="Language is required">
                                           <option value="">Select</option>
                                          @foreach($data['langs'] as $k=>$lan)
                                           <option value="{{ $lan->id }}">{{ $lan->lang_name }}</option>
                                           @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="exampleEmail11" class="">Email</label>
                                        <input name="email"  id="exampleEmail11" placeholder="example@example.com" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required data-parsley-type="email" data-parsley-required-message="Email is required">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="examplePassword11" class="">Password</label>
                                        <input name="password" id="examplePassword" placeholder="**********" type="password" class="form-control @error('password') is-invalid @enderror" required data-parsley-minlength="8" data-parsley-minlength-message="Atleast 8 characters" data-parsley-required-message="Password is required">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="confirmPassword11" class="">Confirm your password</label>
                                        <input name="password_confirmation" id="confirmPassword11" placeholder="**********" type="password" class="form-control" required data-parsley-equalto="#examplePassword" data-parsley-error-message="Password mismatch">
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="form-section"><h5 class="card-title">General information</h5>
                                <div class="form-row">
									<div class="col-md-12">
										<div class="position-relative form-group">
											<label for="last_name" class="">Last Name</label>
											<input name="lastname" id="lastname" placeholder="Smith" type="text" class="form-control" required data-parsley-error-message="Lastname is required">
										</div>
									</div>
									<div class="col-md-12">
										<div class="position-relative form-group">
											<label for="first_name" class="">First Name</label>
											<input name="firstname" id="first_name" placeholder="Alex" type="text" class="form-control" required data-parsley-error-message="Firstname is required">
										</div>
									</div>

									<div class="col-md-6" id="photos">
										<img src="{{ asset('assets/images/avatars/placeholder_profile.jpg') }}" alt="Your current profile picture" title="Your current profile picture" style="width: 100%">
									</div>
									<div class="col-md-6 mt-2">
										<label for="exampleFile">Profile Picture</label>
										<input name="user_img" id="exampleFile" type="file" class="form-control-file" onchange="readFile(this);">
										<small class="form-text text-muted">Recommended size: 400 x 400 pixels. Max file size: 2 MB</small>
									</div>
								</div>
                              </div>
                            <div class="form-row">
                                <div class="col-md-4 offset-md-8 form-navigation">
                                    <button type="button" class="previous btn btn-info pull-left">&lt; Previous</button>
                                    <button type="button" class="next btn btn-info pull-right">Next &gt;</button>
                                     <button type="submit" class="btn btn-primary float-right">Sign up</button>
                                     <span class="clearfix"></span>
                                </div>
                            </div>
                            <hr>
                            @if (Route::has('password.request'))
                            Already have an account?  <a  href="{{ route('teacher.login') }}">Sign in here.</a>
                             @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{ asset('assets/scripts/jquery-3.4.1.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
<script type="text/javascript">
$(function () {
  var $sections = $('.form-section');

  function navigateTo(index) {
    // Mark the current section with the class 'current'
    $sections
      .removeClass('current')
      .eq(index)
        .addClass('current');
    // Show only the navigation buttons that make sense for the current section:
    $('.form-navigation .previous').toggle(index > 0);
    var atTheEnd = index >= $sections.length - 1;
    $('.form-navigation .next').toggle(!atTheEnd);
    $('.form-navigation [type=submit]').toggle(atTheEnd);
  }

  function curIndex() {
    // Return the current index by looking at which section has the class 'current'
    return $sections.index($sections.filter('.current'));
  }

  // Previous button is easy, just go back
  $('.form-navigation .previous').click(function() {
    navigateTo(curIndex() - 1);
  });

  // Next button goes forward iff current block validates
  $('.form-navigation .next').click(function() {
    $('.regForm').parsley().whenValidate({
      group: 'block-' + curIndex()
    }).done(function() {
      navigateTo(curIndex() + 1);
    });
  });

  // Prepare sections by setting the `data-parsley-group` attribute to 'block-0', 'block-1', etc.
  $sections.each(function(index, section) {
    $(section).find(':input').attr('data-parsley-group', 'block-' + index);
  });
  navigateTo(0); // Start at the beginning
});

function readFile(input) {
    counter = input.files.length;
    for (x = 0; x < counter; x++) {
        if (input.files && input.files[x]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#photos").html('<img src="' + e.target.result + '" alt="Your current profile picture" class="img-thumbnail" title="Your current profile picture" style="width: 100%">');
            };
            reader.readAsDataURL(input.files[x]);
        }
    }
}
</script>
@stop
