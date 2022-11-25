@extends('layouts.Admin.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
<style>

</style>
@stop
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <span class="metismenu-icon pe-7s-users"></span>
                </div>
                <div>
                    <h4 class="mb-0 page-title">{{__('messages.student_account')}}</h4>
                    <span class="subtitle"> - {{__('messages.student_account_text')}}</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> > <a href="{{ route('student_details.index') }}">{{__('messages.list_of_students')}}</a> > {{__('messages.last_first_name')}}
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
                <div class="card-body"><h5 class="card-title">{{__('messages.general_information')}}</h5>
                    <form id="student_form" method="POST" class=""  action="{{ route('student_details.store') }}"  data-parsley-validate novalidate enctype="multipart/form-data">
                    @csrf
                        <div class="form-row">
                            <div class="col-md-6">
                                <p>
                                {{__('messages.user_created_on')}} : {{ \Carbon\Carbon::now()->format('d-m-Y') }} at {{ \Carbon\Carbon::now()->format('H:i') }}<br />
                                {{__('messages.by')}} : <a href="javascript:voide(0);">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }} ({{ $user_role }})</a><!--{{__('messages.magento_shop')}}--->
                                </p>
                            </div>
                            <!--<div class="col-md-6">
                                <p>
                                {{__('messages.last_modified_on')}} : 29/03/2020 at 13:15<br />
                                {{__('messages.by')}} : <a href="javascript:voide(0);">Loïc Marin (Admin)</a>
                                </p>
                            </div>--->
                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">{{__('messages.email')}}</label>
                                    {!! Form::email('email','',['id'=>'exampleEmail11','class'=>'form-control','placeholder'=>'example@example.com','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'required']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="examplePassword11" class="">{{__('messages.password')}}</label>
                                    {!! Form::password('password',["data-parsley-minlength"=>"8","data-parsley-maxlength"=>"100",'class'=>'form-control','placeholder'=>'**********','id'=>'examplePassword11','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="last_name" class="">{{__('messages.lastname')}}</label>
                                    {!! Form::text('last_name','',['class'=>'form-control','placeholder'=>'Doe','id'=>'last_name','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="first_name" class="">{{__('messages.firstname')}}</label>
                                    {!! Form::text('first_name','',['class'=>'form-control','placeholder'=>'John','id'=>'first_name','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-3">
                                <div id="photos" class="row"></div>
							</div>
                            <div class="col-md-3">
                                <label for="exampleFile">{{__('messages.profile_picture')}}</label>
                                <input name="file_name" data-url="{{ asset('assets/images/avatars/placeholder_profile.jpg') }}" id="exampleFile" type="file" class="form-control-file" onchange="readFile(this);" >
                                <small class="form-text text-muted">{{__('messages.profile_picture_recommended')}}</small>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="id_magento" class="">{{__('messages.magento_id')}}</label>
                                    {!! Form::text('id_magento','',['class'=>'form-control num_only','placeholder'=>'215485','id'=>'id_magento','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),"data-parsley-validation-threshold"=>"1","data-parsley-trigger"=>"keyup","data-parsley-type"=>"digits",'data-parsley-required']) !!}
                                    <a href="#">{{__('messages.customer_main_file')}}</a>
                                </div>
                                <div class="position-relative form-group">
                                    <label for="language" class="">{{__('messages.preferred_language')}}</label>
									{!! Form::select('language', (isset($lang)?$lang:[]),'',['class'=>'form-control','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required',"data-parsley-errors-container"=>"#language_error"]) !!}
									<span id="language_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="mt-2 btn btn-primary float-right" type="submit">{{__('messages.submit')}}</button>
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
<script>
$(function()
{
	var src_url = $('#exampleFile').data('url');
	$("#photos").html('<div class="col-md-12 col-sm-12 col-xs-12"><img src="'+src_url+'" class="img-thumbnail"></div>');
    $('.select_class_parent').dropdown({
		input:'<input type="text" maxLength="20" placeholder="Search">',
		searchable:true,
		searchNoData: '<li style="color:#ddd">No results found.</li>',
	});
});

$(document).ready(function() {
	$("#student_form").on('submit', function(e){
		e.preventDefault();
		var form = $(this);

		form.parsley().validate();

		if (form.parsley().isValid()){
			document.createElement('form').submit.call(document.getElementById('student_form'));
		}
	});
});
function readFile(input) {
  	$("#status").html('Processing...');
    counter = input.files.length;
		for(x = 0; x<counter; x++){
			if (input.files && input.files[x]) {

				var reader = new FileReader();

				reader.onload = function (e) {
        	$("#photos").html('<div class="col-md-12 col-sm-12 col-xs-12"><img src="'+e.target.result+'" class="img-thumbnail"></div>');
				};

				reader.readAsDataURL(input.files[x]);
			}
    }
    if(counter == x){$("#status").html('');}
  }
</script>
@stop
