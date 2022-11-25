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
               <span class="metismenu-icon pe-7s-display1"></span>
            </div>
            <div>
               <h4 class="mb-0 page-title">{{__('messages.class_account')}}</h4>
               <span class="subtitle"> - {{__('messages.class_account_text')}}</span>
               <div class="page-title-subheading">
                  <nav id="fil-ariane">
                     <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> >
                     <a href="{{ route('class_management.index') }}">{{__('messages.list_of_classes')}}</a>
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
               <form id="class_form" method="POST" class=""  action="{{ route('class_management.store') }}"  data-parsley-validate novalidate enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="slug" id="slug">
                  <div class="form-row">
                     <div class="col-md-6">
                        <p>
                           {{__('messages.class_created_on')}} : {{ \Carbon\Carbon::now()->format('d-m-Y') }} at {{ \Carbon\Carbon::now()->format('H:i') }}<br />
                           {{__('messages.by')}} : <a href="javascript:voide(0);">{{ auth()->user()->firstname }} {{ auth()->user()->lastname }} ({{ $user_role }})</a><!--{{__('messages.magento_shop')}}--->
                        </p>
                     </div>
                     <div class="col-md-12">
                        <hr>
                     </div>
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           {!! Form::label('class_name',__('messages.class_name'),['class'=>'']) !!}
                           {!! Form::text('class_name','',['class'=>'form-control','placeholder'=>__('messages.class_name'),'id'=>'class_name','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           {!! Form::label('select_school',__('messages.school'),['class'=>'']) !!}
                           <div class="select_school_parent">
                              {!! Form::select('school_id', (isset($school)?$school:[]),'',["style"=>"display:none",'id'=>'select_school','class'=>'form-control','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required',"data-parsley-errors-container"=>"#class_error"]) !!}
                           </div>
                           <span id="class_error"></span>
                        </div>
                     </div>
                  </div>
                  <div class="form-row">
                     <div class="col-md-6">
                        <div class="position-relative form-group">
                           {!! Form::label('select_teacher',__('messages.teacher'),['class'=>'']) !!}
                           <div class="select_teacher_parent">
                              {!! Form::select('teacher_id', (isset($teacher)?$teacher:[]),'',["style"=>"display:none",'id'=>'select_teacher','class'=>'form-control','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required',"data-parsley-errors-container"=>"#teacher_error"]) !!}
                           </div>
                           <span id="teacher_error"></span>
                        </div>
                     </div>
                     <div class="col-md-6">
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="position-relative form-check">
                           <input type="checkbox" class="form-check-input" name="display_answer" id="display_answer">
                           <label class="form-check-label" for="display_answer">
                           {{ __('messages.enable_active') }}
                           </label>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        {!! Form::submit(__('messages.submit'), ['name' => 'submit','class'=>'mt-2 btn btn-primary float-right']) !!}
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
	$('.select_school_parent').dropdown({
		input:'<input type="text" maxLength="20" placeholder="Search">',
		searchable:true,
		searchNoData: '<li style="color:#ddd">No results found.</li>',
	});
	$('.select_teacher_parent').dropdown({
		input:'<input type="text" maxLength="20" placeholder="Search">',
		searchable:true,
		searchNoData: '<li style="color:#ddd">No results found.</li>',
	});
   $("#class_name").on("input", function() {
      var str = $(this).val();
      var slug = '';var trimmed = $.trim(str);
      slug = trimmed.replace(/[^a-z0-9-]/gi, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
      slug = slug.toLowerCase();$('#slug').val(slug);
      var matches = str.match(/\b(\w)/g);var acronym = matches.join('');$('#code').val(acronym.toUpperCase());
   });
});
</script>
@stop
