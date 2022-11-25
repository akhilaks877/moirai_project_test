@extends('layouts.Admin.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
   <div class="app-page-title">
      <div class="page-title-wrapper">
         <div class="page-title-heading">
            <div class="page-title-icon">
               <span class="metismenu-icon pe-7s-study"></span>
            </div>
            <div>
               <h4 class="mb-0 page-title">{{__('messages.school_account')}}</h4>
               <span class="subtitle"></span>
               <div class="page-title-subheading">
                  <nav id="fil-ariane">
                     <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> > <a href="{{ route('school_management.index') }}">{{__('messages.list_of_schools')}}</a> > {{__('messages.school_account')}}
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
               <form id="school_form" class=""  action="{{ route('school_management.store') }}" method="POST" data-parsley-validate novalidate>
                    @csrf
                  <div class="form-row">
                     <div class="col-md-6">
                        <p>
                           {{__('messages.school_created_on')}}: {{ \Carbon\Carbon::now()->format('d/m/Y') }} at {{ \Carbon\Carbon::now()->format('H:i') }}<br />
                           {{__('messages.by')}} : <a href="javascript:voide(0);">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }} ({{ (isset($user_role)?$user_role:'') }})</a>
                        </p>
                     </div>
                     <!--<div class="col-md-6">
                        <p>
                           {{__('messages.last_modified_on')}} : 29/03/2020 at 13:15<br />
                           {{__('messages.by')}} : <a href="#">Loïc Marin (Admin)</a>
                        </p>
                     </div>-->
                     <div class="col-md-12">
                        <hr>
                     </div>
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           {!! Form::label('school_name',__('messages.school_name'),['class'=>'']) !!}
                           {!! Form::text('school_name','',['class'=>'form-control','placeholder'=>__('messages.school_name'),'id'=>'school_name','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           {!! Form::label('school_street',__('messages.address'),['class'=>'']) !!}
                           {!! Form::text('school_street','',['class'=>'form-control','placeholder'=>__('messages.sample_address'),'id'=>'school_street','autocomplete'=>'on','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                        </div>
                     </div>
                  </div>
                  <div class="form-row">
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           {!! Form::label('school_city',__('messages.city'),['class'=>'']) !!}
                           {!! Form::text('school_city','',['class'=>'form-control','placeholder'=>__('messages.sample_city_place'),'id'=>'school_city','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           {!! Form::label('school_zipcode',__('messages.postal_code'),['class'=>'']) !!}
                           {!! Form::text('school_zipcode','',['class'=>'form-control','placeholder'=>__('messages.sample_postalcode'),'id'=>'school_zipcode','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                        </div>
                     </div>
                  </div>
                  <div class="form-row">
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           {!! Form::label('school_province',__('messages.province'),['class'=>'']) !!}
                           {!! Form::text('school_province','',['class'=>'form-control','placeholder'=>__('messages.sample_province'),'id'=>'school_province','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           {!! Form::label('school_country',__('messages.country'),['class'=>'']) !!}
                           {!! Form::text('school_country','',['class'=>'form-control','placeholder'=>'Canada','id'=>'school_country','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                        </div>
                     </div>
                  </div>
                  <hr>
                  <h5 class="card-title">{{__('messages.contact_person')}}</h5>
                  <div class="form-row">
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           {!! Form::label('contact_last_name',__('messages.lastname'),['class'=>'']) !!}
                           {!! Form::text('contact_last_name','',['class'=>'form-control','placeholder'=>"Doe",'id'=>'contact_last_name','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           {!! Form::label('contact_first_name',__('messages.firstname'),['class'=>'']) !!}
                           {!! Form::text('contact_first_name','',['class'=>'form-control','placeholder'=>'John','id'=>'contact_first_name','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                        </div>
                     </div>
                  </div>
                  <div class="form-row">
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           {!! Form::label('contact_email',__('messages.email'),['class'=>'']) !!}
                           {!! Form::email('contact_email','',['id'=>'contact_email','class'=>'form-control','placeholder'=>'example@example.com','autocomplete'=>'off',"data-parsley-type-message"=>__('messages.email_type'),'data-parsley-required-message'=>__('messages.val_required'),'required']) !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           {!! Form::label('contact_phone',__('messages.phone_number'),['class'=>'']) !!}
                           {!! Form::text('contact_phone','',['class'=>'form-control','placeholder'=>'514-528-5895','id'=>'contact_phone','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
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
$(document).ready(function() {
	$("#school_form").on('submit', function(e){
		e.preventDefault();
		var form = $(this);

		form.parsley().validate();

		if (form.parsley().isValid()){
			document.createElement('form').submit.call(document.getElementById('school_form'));
		}
	});
});
</script>
@stop
