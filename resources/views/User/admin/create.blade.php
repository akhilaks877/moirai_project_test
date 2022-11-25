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
               <span class="metismenu-icon pe-7s-note2"></span>
            </div>
            <div>
               <h4 class="mb-0 page-title">{{__('messages.admin_account')}}</h4>
               <span class="subtitle"> - {{__('messages.admin_account_text')}}</span>
               <div class="page-title-subheading">
                  <nav id="fil-ariane">
                     <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> > <a href="{{ route('admins.index') }}">{{__('messages.list_of_admin')}}</a> > {{__('messages.last_first_name')}}
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
               <h5 class="card-title">{{__('messages.general_information')}}</h5>
               <form id="admin_form" method="POST" class=""  action="{{ route('admins.store') }}"  data-parsley-validate novalidate>
                  @csrf
                  <div class="form-row">
                     <div class="col-md-6">
                        <p>
                           {{__('messages.user_created_on')}} : {{ \Carbon\Carbon::now()->format('d-m-Y') }} at {{ \Carbon\Carbon::now()->format('H:i') }}<br />
                           {{__('messages.by')}} : <a href="javascript:voide(0);">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }} ({{ $user_role }})</a><!--{{__('messages.magento_shop')}}--->
                        </p>
                     </div>
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
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           <label for="admin_role" class="">{{__('messages.role')}}</label>
                           {!! Form::select('admin_role', (isset($roles)?$roles:[]),'',['id'=>'admin_role','class'=>'form-control custom-select','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required',"data-parsley-errors-container"=>"#admin_error"]) !!}
                           <span id="admin_error"></span>
                        </div>
                     </div>
                     <div class="col-md-6">
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
	$('.select_class_parent').dropdown({
		input:'<input type="text" maxLength="20" placeholder="Search">',
		searchable:true,
		searchNoData: '<li style="color:#ddd">No results found.</li>',
	});
	$("#admin_form").on('submit', function(e){
		e.preventDefault();
		var form = $(this);

		form.parsley().validate();

		if (form.parsley().isValid()){
			document.createElement('form').submit.call(document.getElementById('admin_form'));
		}
	});
});

</script>
@stop
