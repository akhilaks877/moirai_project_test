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
               <h4 class="mb-0 page-title">{{__('messages.class_account')}}
                  <span class="uppercase">{{ (isset($model)?$model->class_name:'') }}<span>
               </h4>
               <span class="subtitle"> - {{__('messages.class_account_text')}}
               <span class="uppercase">{{ (isset($model)?$model->class_name:'') }}</span>.</span>
               <div class="page-title-subheading">
                  <nav id="fil-ariane">
                     <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> >
                     <a href="{{ route('class_management.index') }}">
                     {{__('messages.list_of_classes')}}</a> >
                     <span class="uppercase">{{ (isset($model)?$model->class_name:'') }}<span>
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
               {!! Form::model($model, ['route' => ['class_management.update', $model->id], 'method' => 'patch','class'=>'','id'=>'class_form','data-parsley-validate','novalidate', 'files' => true]) !!}
               @csrf
			   <input type="hidden" name="slug" id="slug" value="{{ (isset($model)?$model['slug']:'') }}">
               <div class="form-row">
                  <div class="col-md-6">
                     <p>
                        {{__('messages.school_created_on')}}: {{ (isset($model)?date('d/m/Y',strtotime($model['created_at'])):'') }} at {{ (isset($model)?date('H:i',strtotime($model['created_at'])):'') }}<br />
                        {{__('messages.by')}} : <a href="javascript:void(0)">{{ (isset($model->created_user)?$model->created_user->firstname:'') }} {{ (isset($model->created_user)?$model->created_user->lastname:'') }} ({{ (isset($model->created_user->roles)?$model->created_user->roles->first()->name:'') }})</a>
                     </p>
                  </div>
                  <div class="col-md-6">
                     <p>
                        {{__('messages.last_modified_on')}} : {{ (isset($model['updated_at'])?date('d/m/Y',strtotime($model['updated_at'])):'') }} at {{ (isset($model['updated_at'])?date('H:i',strtotime($model['updated_at'])):'') }}<br />
                        {{__('messages.by')}} : <a href="javascript:void(0)">{{ (isset($model->updated_user)?$model->updated_user->firstname:'') }} {{ (isset($model->updated_user)?$model->updated_user->lastname:'') }} ({{ (isset($model->updated_user->roles)?$model->updated_user->roles->first()->name:'') }})</a>
                     </p>
                  </div>
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        {!! Form::label('class_name',__('messages.class_name'),['class'=>'']) !!}
                        {!! Form::text('class_name',(isset($model)?$model['class_name']:''),['class'=>'form-control','placeholder'=>__('messages.class_name'),'id'=>'class_name','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        {!! Form::label('select_school',__('messages.school'),['class'=>'']) !!}
                        <div class="select_school_parent">
                          {!! Form::select('school_id', (isset($school)?$school:[]),(isset($model)?$model['school_id']:''),["style"=>"display:none",'id'=>'select_school','class'=>'form-control','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required',"data-parsley-errors-container"=>"#class_error"]) !!}
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-row">
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        {!! Form::label('select_teacher',__('messages.teacher'),['class'=>'']) !!}
                        <div class="select_teacher_parent">
						  {!! Form::select('teacher_id', (isset($teacher)?$teacher:[]),(isset($model)?$model['teacher_id']:''),["style"=>"display:none",'id'=>'select_teacher','class'=>'form-control','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required',"data-parsley-errors-container"=>"#teacher_error"]) !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <div class="position-relative form-check">
                        <input type="checkbox" class="form-check-input" name="display_answer" id="display_answer" {{  ($model->display_answer == 1 ? ' checked' : '') }}>
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
               {!! Form::close() !!}
               <div class="row">
                  <div class="col-md-12 mb-4">
                     <hr>
                     <h5 class="mt-4"> {{ __('messages.list_of_students') }} ({{ (isset($model['students_count'])?$model['students_count']:'') }})</h5>
					  <table id="first" class="mb-0 table table-hover display table-bordered" data-url="{{ route('class_management.student_lists') }}">
                        <thead>
                           <tr>
                              <th>{{__('messages.email')}}</th>
                              <th>{{__('messages.lastname')}}</th>
                              <th>{{__('messages.firstname')}}</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                     </table>
                  </div>
               </div>
               <form data-parsley-validate novalidate id="status_form" method="POST" action="{{ route('class_management.addstudent') }}">
               <div class="row d-flex">
               @csrf
               {{ Form::hidden('class_id',$model['id']) }}
                  <div class="col-md-8">
                     <h5>{{__('messages.choose_student')}}</h5>
                     <label for="select_student" class="">{{__('messages.choose_students')}}</label>
                     <div class="select_student_parent">
                        <select style="display:none"  name="select_student[]" data-parsley-required multiple id="select_student"
                   data-parsley-required-message="{{ __('messages.val_required') }}" data-parsley-errors-container="#class_error">
                     @if(isset($students))
                        @foreach($students as $key => $val)
                        @php $disabled = ''; @endphp
                        @if(in_array($key, $stud_class))
                           @php $disabled = 'disabled'; @endphp
                        @endif
                           <option value="{{ $key }}" {{ $disabled }}>{{ $val }}</option>
                        @endforeach
                     @endif
                  </select>
                     </div>
                  </div>
                  <div class="col-md-4 align-self-end mt-2">
                     <a href="javascript:void(0);"  onclick="$(this).closest('form').submit()" id="add_stud_to_class" class="btn btn-primary float-right">
                     <span class="fa fa-plus"></span>&nbsp;&nbsp;{{__('messages.add_new_stud_to_classes')}}</a>
                  </div>
                  <span id="class_error"></span>
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
$(function(){
    $('.select_student_parent').dropdown({
		input:'<input type="text" maxLength="20" placeholder="Search">',
		searchable:true,
		searchNoData: '<li style="color:#ddd">No results found.</li>',
	});

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

   $("#class_form").on('submit', function(e){
		e.preventDefault();
		var form = $(this);
		form.parsley().validate();
		if (form.parsley().isValid()){
			document.createElement('form').submit.call(document.getElementById('class_form'));
		}
	});

	//
	$.fn.dataTable.ext.errMode = 'throw';
    $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) {
        alert('Please reload the page!');
    };
    var oTable = '';
    $('.display').each(function()
    {
        var lang_id = $(this).attr('id');
        var url = $(this).data('url');
		if(lang_id=='first'){
			var pageVal = false;
			var column = [
				{data: 'email'},
				{data: 'lastname'},
				{data: 'firstname'},
				{data: 'action', name: 'action', orderable: false, searchable: false},
			];
		}
        oTable = $("#" + lang_id).DataTable({
            processing: true,
            paging: pageVal,
            info: true,
            ordering: false,
            dom: "rtp",
            language: {  "emptyTable": "{{__('messages.no_data_table')}}",paginate: { next: '&raquo;', previous: '&laquo;' } },
            serverSide: true,
            searching: true,
            ajax:{ url: url,data: function(d) {d.classID = "{{ $model->id }}";d.LanguageID = lang_id;d._token =  "{{ csrf_token() }}";}},
            columns: column,
            fnDrawCallback: function( oSettings ) { },
               "createdRow": function(row, data, dataIndex){ $('[data-toggle="tooltip"]', row).tooltip(); },
            });
    });

    //
   $("#status_form").on('submit', function(e){
		e.preventDefault();
		var form = $(this);
		form.parsley().validate();
		if (form.parsley().isValid()){
			document.createElement('form').submit.call(document.getElementById('status_form'));
		}
	});
	//

});
</script>
@stop
