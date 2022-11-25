@extends('layouts.Teacher.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
   <div class="app-page-title">
      <div class="page-title-wrapper">
         <div class="page-title-heading">
            <div class="page-title-icon">
               <span class="pe-7s-id text-success">
               </span>
            </div>
            <div>
               <h4 class="mb-0 page-title">{{__('messages.myaccount')}}</h4>
               <span class="subtitle"> - {{__('messages.moiraï_administrator_info')}}</span>
               <div class="page-title-subheading">
                  <nav id="fil-ariane">
                     <a href="{{ route('teacher.dashboard.index') }}">{{__('messages.home')}}</a> > {{__('messages.myaccount')}}
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('layouts.Admin.messages')
   <div class="tab-content">
      <div class="tab-pane tabs-animation fade show active">
         <div class="main-card mb-3 card">
            <div class="card-body">
               <h5 class="card-title">{{__('messages.myprofile')}}</h5>
               <form class="" method="POST" id="account-f" enctype="multipart/form-data">
                  @csrf
                  @method('PATCH')
                  <div class="form-row">
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           <label for="exampleEmail11" class="">{{__('messages.email')}}</label>
                           <input name="email" id="exampleEmail11" placeholder="example@example.com" value="{{ Auth::user()->email }}" type="email" class="form-control" required>
                        </div>
                        <span class="error_bag" id="error_email" style="color: darkred"></span>
                     </div>
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           <label for="examplePassword11" class="">{{__('messages.password')}}</label>
                           <input name="password" id="examplePassword11" placeholder="**********" type="password" class="form-control">
                        </div>
                        <span class="error_bag" id="error_password" style="color: darkred"></span>
                     </div>
                  </div>
                  <div class="form-row">
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           <label for="last_name" class="">{{__('messages.lastname')}}</label>
                           <input name="lastname" id="lastname" placeholder="Raguenez" value="{{ Auth::user()->lastname }}" type="text" class="form-control" required>
                        </div>
                        <span class="error_bag" id="error_lastname" style="color: darkred"></span>
                     </div>
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           <label for="first_name" class="">{{__('messages.firstname')}}</label>
                           <input name="firstname" id="firstname" placeholder="Frédéric" value="{{ Auth::user()->firstname }}" type="text" class="form-control" required>
                        </div>
                        <span class="error_bag" id="error_firstname" style="color: darkred"></span>
                     </div>
                  </div>
                  <div class="form-row">
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           <label for="langue" class="">{{__('messages.preferred_language')}}</label>
                           <select name="preferred_language" id="preferred_language" class="form-control">
                           @foreach($data['langs'] as $k=>$lan)
                           <option value="{{ $lan->id }}" @if(Auth::user()->preferred_language == $lan->id) selected @endif>{{ $lan->lang_name }}</option>
                           @endforeach
                           </select>
                        </div>
                        <span class="error_bag"id="error_preferred_language" style="color: darkred"></span>
                     </div>
                     <div class="col-md-6">
                     </div>
                  </div>
                  <div class="form-row">
                     <div class="col-md-3">
                           <!--<img src="{{ asset('assets/images/avatars/placeholder_profile.jpg') }}" alt="Your current profile picture" title="Your current profile picture" style="width: 100%" />--->
                           <div id="photos" class="">
                           <div class="col-md-12 col-sm-12 col-xs-12">
                           @if(Storage::exists('public/user_profiles'.'/'.Auth::user()->user_img))
                                 <img class="img-thumbnail" src="{{ (isset(Auth::user()->user_img)&&(Auth::user()->user_img!='')?asset('storage/user_profiles/'.Auth::user()->user_img):asset('assets/images/avatars/placeholder_profile.jpg')) }}" alt="Your current profile picture" title="Your current profile picture" style="width: 100%" />
                           @else
                           <img class="img-thumbnail" src="{{ (asset('assets/images/avatars/placeholder_profile.jpg')) }}" alt="Your current profile picture" title="Your current profile picture" style="width: 100%" />
                           @endif
                           </div>
                        </div>
                     </div>
                     <div class="col-md-9">
                        <label for="exampleFile">{{__('messages.profile_picture')}}</label>
                        <input name="user_img" id="exampleFile" type="file" class="form-control-file" onchange="readFile(this);">
                        <small class="form-text text-muted">{{__('messages.profile_picture_recommended')}}</small>
                     </div>
                     <span class="error_bag" id="error_user_img" style="color: darkred"></span>
                  </div>
                  <button type="submit" class="mt-2 btn btn-primary float-right">{{__('messages.submit')}}</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@stop
@section('page-script')
<script>
$(function() {
    $('#account-f').on('submit', function(e) {
        e.preventDefault();
        $('span.error_bag').html('');
        uid = "{{ Auth()->user()->id }}";
        url = "{{ route('teacher.my-account.update',":id") }}";
        url = url.replace(':id', uid);
        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            dataType: 'JSON',
            data: new FormData($(this)[0]),
            processData: false,
            contentType: false,
            cache: false,
            success: function(response) {
                if (response.resultant == 'validation') {
                    $.each(response.messages, function(key, value) {
                        $('span#error_' + key).html(value);
                    });
                }
                if (response.resultant == 'success') {
                    window.location.reload(true)
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                }
            },
            fail: function() {}
        });
    });
});
function readFile(input) {
    //$("#status").html('Processing...');
    counter = input.files.length;
    for (x = 0; x < counter; x++) {
        if (input.files && input.files[x]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#photos").html('<div class="col-md-12 col-sm-12 col-xs-12"><img src="' + e.target.result + '" class="img-thumbnail"></div>');
            };
            reader.readAsDataURL(input.files[x]);
        }
    }
   //  if (counter == x) {
   //      $("#status").html('');
   //  }
}
</script>
@stop
