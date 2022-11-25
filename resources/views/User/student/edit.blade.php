@extends('layouts.Admin.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
<style>
table.dataTable {
    border-collapse: collapse !important;
}
.pagination{
    display: inline-flex;
}
div.dataTables_wrapper div.dataTables_paginate{
    text-align: center;
}
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
               <h4 class="mb-0 page-title">{{__('messages.student_account')}}
				   <span class="uppercase">{{ (isset($student)?$student->last_name:'') }},
				   {{ (isset($student)?$student->first_name:'') }}<span></h4>
               <span class="subtitle"> - {{__('messages.student_account_text')}}
			   <span class="uppercase">{{ (isset($student)?$student->last_name:'') }},
				   {{ (isset($student)?$student->first_name:'') }}</span></span>
               <div class="page-title-subheading">
                  <nav id="fil-ariane">
                     <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> >
					 <a href="{{ route('student_details.index') }}">
					 {{__('messages.list_of_students')}}</a> >
					 <span class="uppercase">{{ (isset($student)?$student->last_name:'') }},
				   {{ (isset($student)?$student->first_name:'') }}</span>
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
               {!! Form::model($student, ['route' => ['student_details.update', $student->id], 'method' => 'patch','class'=>'','id'=>'student_form','data-parsley-validate','novalidate', 'files' => true]) !!}
               @csrf
               <div class="form-row">
                  <div class="col-md-6">
                     <p>
                        {{__('messages.user_created_on')}} : {{ (isset($student)?date('d/m/Y',strtotime($student['created_at'])):'') }} at {{ (isset($student)?date('H:i',strtotime($student['created_at'])):'') }}<br />
                        {{__('messages.by')}} : {{__('messages.magento_shop')}}
                     </p>
                  </div>
                  <div class="col-md-6">
                     <p>
                        {{__('messages.last_modified_on')}} : {{ (isset($student['updated_at'])?date('d/m/Y',strtotime($student['updated_at'])):'') }} at {{ (isset($student['updated_at'])?date('H:i',strtotime($student['updated_at'])):'') }}<br />
                        {{__('messages.by')}} : <a href="javascript:void(0)">{{ (isset($student->updated_user)?$student->updated_user->firstname:'') }} {{ (isset($student->updated_user)?$student->updated_user->lastname:'') }} ({{ (isset($student->updated_user->roles)?$student->updated_user->roles->first()->name:'') }})</a>
                     </p>
                  </div>
                  <div class="col-md-12">
                     <hr>
                  </div>
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        <label for="exampleEmail11" class="">{{__('messages.email')}}</label>
                        {!! Form::email('email',(isset($student)?$student['email']:''),['id'=>'exampleEmail11','class'=>'form-control','placeholder'=>'example@example.com','autocomplete'=>'off','data-parsley-required-message'=>__('validation.email'),'required']) !!}
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        <label for="examplePassword11" class="">{{__('messages.password')}}</label>
                        {!! Form::password('password',['class'=>'form-control','placeholder'=>'**********','id'=>'examplePassword11','autocomplete'=>'off','data-parsley-required-message'=>'Please enter password']) !!}
                     </div>
                  </div>
               </div>
               <div class="form-row">
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        <label for="last_name" class="">{{__('messages.lastname')}}</label>
                        {!! Form::text('last_name',(isset($student)?$student['last_name']:''),['class'=>'form-control','placeholder'=>'Doe','id'=>'last_name','autocomplete'=>'off','data-parsley-required-message'=>'Please enter your lastname','data-parsley-required']) !!}
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        <label for="first_name" class="">{{__('messages.firstname')}}</label>
                        {!! Form::text('first_name',(isset($student)?$student['first_name']:''),['class'=>'form-control','placeholder'=>'John','id'=>'first_name','autocomplete'=>'off','data-parsley-required-message'=>'Please enter your firstname','data-parsley-required']) !!}
                     </div>
                  </div>
               </div>
               <div class="form-row">
                  <div class="col-md-3">
                     <div id="photos" class="">
                        <div class="col-md-12 col-sm-12 col-xs-12">
							@if(Storage::exists('public/students'.'/'.$student->file))
							<img class="img-thumbnail" src="{{ (isset($student)&&($student->file!='')?asset('storage/students/'.$student['file']):asset('assets/images/avatars/placeholder_profile.jpg')) }}" alt="Your current profile picture" title="Your current profile picture" style="width: 100%" />
							@else
							<img class="img-thumbnail" src="{{ (asset('assets/images/avatars/placeholder_profile.jpg')) }}" alt="Your current profile picture" title="Your current profile picture" style="width: 100%" />
							@endif
                           <!--<img class="img-thumbnail" src="{{ (isset($student)&&($student->file!='')?asset('uploads/students/'.$student['file']):asset('assets/images/avatars/placeholder_profile.jpg')) }}" alt="Your current profile picture" title="Your current profile picture" style="width: 100%" />--->

						</div>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <label for="exampleFile">{{__('messages.profile_picture')}}</label>
                     <input name="file_name" id="exampleFile" type="file" class="form-control-file" onchange="readFile(this);">
                     <small class="form-text text-muted">{{__('messages.profile_picture_recommended')}}</small>
                  </div>
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        <label for="id_magento" class="">{{__('messages.magento_id')}}</label>
                        {!! Form::text('id_magento',(isset($student)?$student['id_magento']:''),['class'=>'form-control num_only','placeholder'=>'215485','id'=>'id_magento','autocomplete'=>'off','data-parsley-required-message'=>'Please enter your Magento ID',"data-parsley-validation-threshold"=>"1","data-parsley-trigger"=>"keyup","data-parsley-type"=>"digits",'data-parsley-required']) !!}
                        <a href="#">{{__('messages.customer_main_file')}}</a>
                     </div>
                     <div class="position-relative form-group">
                        <label for="language" class="">{{__('messages.preferred_language')}}</label>
                        {!! Form::select('language', (isset($lang)?$lang:[]),(isset($student)?$student['language']:''),['class'=>'form-control','data-parsley-required-message'=>'Please select preferred language','data-parsley-required',"data-parsley-errors-container"=>"#language_error"]) !!}
                        <span id="language_error"></span>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     {!! Form::submit(__('messages.submit'), ['name' => 'submit','class'=>'mt-2 btn btn-primary float-right']) !!}
                  </div>
               </div>
               {!! Form::close() !!}
               <!--listofclass-->
               <div class="row">
                  <div class="col-md-12">
                     <hr>
                     <h5 class="mt-4">{{__('messages.list_of_classes')}}</h5>
                     <table id="first" class="mb-0 table table-hover display table-bordered" data-url="{{ route('student_details.class_lists') }}">
                        <thead>
                           <tr>
                              <th>{{__('messages.class_name')}}</th>
                              <th>{{__('messages.teacher')}}</th>
                              <th>Action</th>
                           </tr>
                        </thead>
                     </table>
                  </div>
               </div>
               <!--listofclass-->
			   <!--addclass-->
			   <form data-parsley-validate novalidate id="status_form" method="POST" action="{{ route('student_details.addtoclass') }}">
			   <div class="row d-flex">
				@csrf
            {{ Form::hidden('student_id',$student['id']) }}
				   <div class="col-md-8">
					  <h5>{{__('messages.choose_class')}}</h5>
					  {!! Form::label('select_class',__('messages.choose_classes'),['class'=>'']) !!}
					  <div class="select_class_parent">
                   <select style="display:none"  name="select_class[]" data-parsley-required multiple id="select_class"
                   data-parsley-required-message="{{ __('messages.val_required') }}" data-parsley-errors-container="#class_error">
                     @if(isset($classes))
                        @foreach($classes as $key => $val)
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
					  <span class="fa fa-plus"></span>&nbsp;{{__('messages.add_these_stud_to_classes')}}
					  </a>
				   </div>
				   <span id="class_error"></span>
				</div>
				</form>
			   <!--addclass-->
			   <!--completed excercises-->
			   <div class="row">
				   <div class="col-md-12">
					  <hr>
					  <h5 class="mt-4">{{__('messages.list_complete_excercise')}}</h5>
					  <table id="second" class="mb-0 table table-hover display table-bordered" data-url="{{ route('student_details.class_excercise_lists') }}">
						 <thead>
							<tr>
							   <th>{{__('messages.excercise_name')}}</th>
							   <th>{{__('messages.class_name')}}</th>
							   <th>{{__('messages.book_title')}}</th>
							   <th>{{__('messages.chapter')}}</th>
							   <th>{{__('messages.grade')}}</th>
							   <th>Action</th>
							</tr>
						 </thead>
						 <tbody>
							<tr>
							   <td>Lorem ipsum dolor sit amet</td>
							   <td>Class 1</td>
							   <td>Book Title 1</td>
							   <td>1.2</td>
							   <td>4/8</td>
							   <td>
								  <a href="exercice_result.html" title="View grades" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-eye"></span></a>
								  <a href="#reset" title="Reset exercise" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-history"></span></a>
							   </td>
							</tr>
							<tr>
							   <td>Lorem ipsum dolor sit amet</td>
							   <td>Class 1</td>
							   <td>Book Title 2</td>
							   <td>2.3</td>
							   <td>3/6</td>
							   <td>
								  <a href="exercice_result.html" title="Correct exercise" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-check"></span></a>
							   </td>
							</tr>
						 </tbody>
					  </table>
				   </div>
				</div>
			   <!--completed excercises-->
            </div>
         </div>
      </div>
   </div>
</div>
@stop
@section('page-script')
<script>
$('.select_class_parent').dropdown({
	input:'<input type="text" maxLength="20" placeholder="Search">',
	searchable:true,
	searchNoData: '<li style="color:#ddd">No results found.</li>',
});
/*$(function(){
    $('.select_class_parent').dropdown({
	input:'<input type="text" maxLength="20" placeholder="Search">',
	searchable:true,
 	searchNoData: '<li style="color:#ddd">No results found.</li>',
  });
});*/

$(document).ready(function()
{
	$("#student_form").on('submit', function(e){
		e.preventDefault();
		var form = $(this);
		form.parsley().validate();
		if (form.parsley().isValid()){
			document.createElement('form').submit.call(document.getElementById('student_form'));
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
				{data: 'class_name'},
				{data: 'teacher'},
				{data: 'action', name: 'action', orderable: false, searchable: false},
			];
		}else{
			var pageVal = true;
			var column = [
				{data: 'excercise_name'},
				{data: 'class_name'},
				{data: 'book_title'},
				{data: 'chapter'},
				{data: 'grade'},
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
            ajax:{ url: url,data: function(d) {d.student_id="{{ $student->id }}",d.LanguageID = lang_id;d._token =  "{{ csrf_token() }}";}},
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
