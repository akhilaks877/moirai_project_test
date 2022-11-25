@extends('layouts.Admin.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2@2.0.0/dist/spectrum.min.css">
@stop
<!--@if(session()->has('prefstatus.status'))
<div class="alert alert-{{ session('prefstatus.status') }}">
{!! session('prefstatus.message') !!}
</div>
@endif--->
<div class="app-main__inner">
   <div class="app-page-title">
      <div class="page-title-wrapper">
         <div class="page-title-heading">
            <div class="page-title-icon">
               <span class="pe-7s-look text-success">
               </span>
            </div>
            <div>
               <h4 class="mb-0 page-title">{{__('messages.accessibility')}}</h4>
               <span class="subtitle"> - {{__('messages.accessibility_text')}}</span>
               <div class="page-title-subheading">
                  <nav id="fil-ariane">
                     <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> >
					 {{__('messages.accessibility')}}
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </div>
   @if(session()->has('prefstatus.status'))
   <div class="alert alert-{{ session('prefstatus.status') }}">
      {!! session('prefstatus.message') !!}
   </div>
   @endif
   @include('layouts.Admin.messages')
   <div class="tab-content">
						<div class="tab-pane tabs-animation fade show active">
							<div class="main-card mb-3 card">
								<div class="card-body">
									<h5 class="card-title">My Accessibility Settings</h5>
									<form class="" action="" method="POST"  data-url="" id="preffsett" data-parsley-validate>
                                       @csrf
                                       @method('PATCH')
                                        <div class="row">
											<div class="col-12">
												<h6>Notifications</h6>
											</div>
											<div class="col-12">
                                               <input type="checkbox" id="active_notification" name="show_notification" value="{{ ($pref_setts->show_notification == 1)?1:2 }}" @if($pref_setts->show_notification == 1) checked @endif data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="primary" >
												<label class="form-check-label pl-2" for="active_notification">Enable notifications</label>
											</div>
										</div>

										<div class="row">
											<div class="col-12">
												<hr>
												<h6>Font Preferences</h6>
											</div>
											<div class="col-md-6">
												<div class="position-relative form-group text-center">
													<p>Decrease font size</p>
													<a href="javascript:void(0);" class="decFont manip mt-2 btn btn-primary">A <sup>-</sup></a>
												</div>
											</div>
											<div class="col-md-6">
												<div class="position-relative form-group text-center">
													<p>Increase font size</p>
													<a href="javascript:void(0);" class="incFont manip mt-2 btn btn-primary">A <sup>+</sup></a>
												</div>
                                            </div>
                                            <input type="hidden" name="selected_font_size" value="{{ $pref_setts->selected_font_size }}">
                                            <div class="col-12">
												<h6 class="fontsize_chosen">Selected Font Size : <strong>{{ $pref_setts->selected_font_size }}</strong></h6>
											</div>
										</div>

										<div class="row">
											<div class="col-12">
												<hr>
												<h6>Menu Preferences</h6>
											</div>
											<div class="col-md-6">
												<div class="position-relative form-group">
													<label for="background-color_menu" class="">Menu background colour: </label>
													<input id='background-color_menu' name="menu_back_color" value="{{ ($pref_setts->menu_back_color) ?$pref_setts->menu_back_color:'#FFFFFF' }}"/>
												</div>
											</div>
											<div class="col-md-6">
												<div class="position-relative form-group">
													<label for="font-color_menu" class="">Menu text colour: </label>
													<input id='font-color_menu'  name="menu_text_color" value="{{ ($pref_setts->menu_text_color) ?$pref_setts->menu_text_color:'#343a40' }}"/>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-12">
												<hr>
												<h6>Reading Preferences</h6>
											</div>
											<div class="col-md-6">
												<div class="position-relative form-group">
													<label for="background-color_readings" class="">Reading background colour: </label>
													<input id='background-color_readings' name="readng_back_color" value="{{ ($pref_setts->readng_back_color) ?$pref_setts->readng_back_color:'#FFFFFF' }}"/>
												</div>
											</div>
											<div class="col-md-6">
												<div class="position-relative form-group">
													<label for="font-color_readings" class="">Reading text colour: </label>
													<input id='font-color_readings' name="readng_text_color" value="{{ ($pref_setts->readng_text_color) ?$pref_setts->readng_text_color:'#000000' }}"/>
												</div>
											</div>

										</div>

										<div class="row">
											<div class="col-12">
												<hr>
												<h6>Image Preferences</h6>
											</div>
											<div class="col-12 mt-4 mb-4 text-center">
											<fieldset>
												<div role="group" class="btn-group btn-group btn-group-toggle" data-toggle="buttons">

													<label class="btn btn-primary {{ ($pref_setts->image_preference== 1)? 'active' : '' }}">
														<input type="radio" name="image_preference" value="1" {{ ($pref_setts->image_preference== 1)? "checked" : "" }} id="image_default">
														<!-- If this is selected, none of the styling classes are applied to the images -->
														<label for="image_default" class="mb-0">Default images</label>
													</label>
													<label class="btn btn-primary {{ ($pref_setts->image_preference== 2)? 'active' : '' }}">
														<input type="radio" name="image_preference" value="2" {{ ($pref_setts->image_preference== 2)? "checked" : "" }} id="image_gray">
														<!-- If this is selected, the class .grayscale needs to be added to each image every time a page is loaded -->
														<label for="image_gray" class="mb-0">Images in grayscale (black & white)</label>
													</label>
													<label class="btn btn-primary {{ ($pref_setts->image_preference== 3)? 'active' : '' }}">
														<input type="radio" name="image_preference" value="3" {{ ($pref_setts->image_preference== 3)? "checked" : "" }} id="image_inverted">
														<!-- If this is selected, every image need to be added the class .inverted everytime a page is loaded -->
														<label for="image_inverted" class="mb-0">Images in inverted (negative) colours</label>
													</label>
												</div>
											</fieldset>
											</div>

											<div class="col-12">
                                                <button type="submit" class="mt-2 btn btn-primary float-right">Save Preferences</button>
												{{-- <a href="#nogo" class="mt-2 btn btn-primary float-right">Save Preferences</a> --}}
											</div>





										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
</div>
@stop
@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2@2.0.0/dist/spectrum.min.js"></script>
<script>
    $("#background-color_menu").spectrum({
    type: "color",
		move: function(color) {
			$(".scrollbar-sidebar").css("background-color",  color.toHexString());
		}
	},
    );
    $("#font-color_menu").spectrum({
    type: "color",
		move: function(color) {
			$(".metismenu li a ").css("color",  color.toHexString());
		}
	});
    $("#font-color_readings").spectrum({
        type: "color"
    });
    $("#background-color_readings").spectrum({
        type: "color"
    });

    $("#active_notification").on('change',function(){
        if ($(this).is(':checked')) {
            $(this).val(1);
    }
    else {
        $(this).val(2);
    }
    });

    $(document).on('click','a.manip',function(){
     // alert($(this).attr('class'));
     var selecteDfntsize=$('input[name="selected_font_size"]');
     var defaultfntsize="{{ $pref_setts->default_font_size }}";
     if($(this).hasClass('incFont') && Number(selecteDfntsize.val()) < 25 &&  Number(selecteDfntsize.val()) >= defaultfntsize){
        selecteDfntsize.val(Number(selecteDfntsize.val())+1);
     }
     if($(this).hasClass('decFont') && Number(selecteDfntsize.val()) <= 25 && Number(selecteDfntsize.val()) > defaultfntsize){
        selecteDfntsize.val(Number(selecteDfntsize.val())-1);
     }
     if(selecteDfntsize.val() === ''){
        selecteDfntsize.val(Number(10));
     }
     $('h6.fontsize_chosen strong').html(selecteDfntsize.val());
    })

    $('#preffsett').on('submit', function(e) {
        e.preventDefault();
        // $('span.error_bag').html('');
        uid = "{{ $pref_setts->id }}";
        url = "{{ route('admin.accessibility.update',":id") }}";
        url = url.replace(':id', uid);
        $.ajax({
            url:url,
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
     type: "PATCH",
     data: $(this).serialize(),
     dataType: 'json',
     success: function(response){
        if(response.status == 'success'){
            window.location.reload(true);
         }
        if(response.status == 'invalid'){
         }
     },
        });
    });
</script>
@stop
