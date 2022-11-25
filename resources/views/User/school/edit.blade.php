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
               <span class="metismenu-icon pe-7s-study"></span>
            </div>
            <div>
               <h4 class="mb-0 page-title">{{__('messages.school_account')}} : {{(isset($school)?strtoupper($school->school_name):'')}}</h4>
               <span class="subtitle"> - {{__('messages.this_page_will_school')}} {{(isset($school)?strtoupper($school->school_name):'')}}.</span>
               <div class="page-title-subheading">
                  <nav id="fil-ariane">
                     <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> > <a href="{{ route('school_management.index') }}">{{__('messages.list_of_schools')}}</a> > {{(isset($school)?strtoupper($school->school_name):'')}}
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
               {!! Form::model($school, ['route' => ['school_management.update', $school->id], 'method' => 'patch','class'=>'','id'=>'school_form','data-parsley-validate','novalidate']) !!}
               @csrf
               <div class="form-row">
                  <div class="col-md-6">
                     <p>
                        {{__('messages.school_created_on')}}: {{ (isset($school)?date('d/m/Y',strtotime($school['created_at'])):'') }} at {{ (isset($school)?date('H:i',strtotime($school['created_at'])):'') }}<br />
                        {{__('messages.by')}} : <a href="javascript:void(0)">{{ (isset($school->created_user)?$school->created_user->firstname:'') }} {{ (isset($school->created_user)?$school->created_user->lastname:'') }} ({{ (isset($school->created_user->roles)?$school->created_user->roles->first()->name:'') }})</a>
                     </p>
                  </div>
                  <div class="col-md-6">
                     <p>
                        {{__('messages.last_modified_on')}} : {{ (isset($teacher['updated_at'])?date('d/m/Y',strtotime($school['updated_at'])):'') }} at {{ (isset($school['updated_at'])?date('H:i',strtotime($school['updated_at'])):'') }}<br />
                        {{__('messages.by')}} : <a href="javascript:void(0)">{{ (isset($school->updated_user)?$school->updated_user->firstname:'') }} {{ (isset($school->updated_user)?$school->updated_user->lastname:'') }} ({{ (isset($school->updated_user->roles)?$school->updated_user->roles->first()->name:'') }})</a>
                     </p>
                  </div>
                  <div class="col-md-12">
                     <hr>
                  </div>
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        {!! Form::label('school_name',__('messages.school_name'),['class'=>'']) !!}
                        {!! Form::text('school_name',(isset($school)?$school['school_name']:''),['class'=>'form-control','placeholder'=>__('messages.school_name'),'id'=>'school_name','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        {!! Form::label('school_street',__('messages.address'),['class'=>'']) !!}
                        {!! Form::text('school_street',(isset($school)?$school['school_street']:''),['class'=>'form-control','placeholder'=>__('messages.sample_address'),'id'=>'school_street','autocomplete'=>'on','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                     </div>
                  </div>
               </div>
               <div class="form-row">
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        {!! Form::label('school_city',__('messages.city'),['class'=>'']) !!}
                        {!! Form::text('school_city',(isset($school)?$school['school_city']:''),['class'=>'form-control','placeholder'=>__('messages.sample_city_place'),'id'=>'school_city','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        {!! Form::label('school_zipcode',__('messages.postal_code'),['class'=>'']) !!}
                        {!! Form::text('school_zipcode',(isset($school)?$school['school_zipcode']:''),['class'=>'form-control','placeholder'=>__('messages.sample_postalcode'),'id'=>'school_zipcode','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                     </div>
                  </div>
               </div>
               <div class="form-row">
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        {!! Form::label('school_province',__('messages.province'),['class'=>'']) !!}
                        {!! Form::text('school_province',(isset($school)?$school['school_province']:''),['class'=>'form-control','placeholder'=>__('messages.sample_province'),'id'=>'school_province','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        {!! Form::label('school_country',__('messages.country'),['class'=>'']) !!}
                        {!! Form::text('school_country',(isset($school)?$school['school_country']:''),['class'=>'form-control','placeholder'=>'Canada','id'=>'school_country','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                     </div>
                  </div>
               </div>
               <hr>
               <h5 class="card-title">{{__('messages.contact_person')}}</h5>
               <div class="form-row">
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        {!! Form::label('contact_last_name',__('messages.lastname'),['class'=>'']) !!}
                        {!! Form::text('contact_last_name',(isset($school)?$school['contact_last_name']:''),['class'=>'form-control','placeholder'=>"Doe",'id'=>'contact_last_name','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        {!! Form::label('contact_first_name',__('messages.firstname'),['class'=>'']) !!}
                        {!! Form::text('contact_first_name',(isset($school)?$school['contact_first_name']:''),['class'=>'form-control','placeholder'=>'John','id'=>'contact_first_name','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                     </div>
                  </div>
               </div>
               <div class="form-row">
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        {!! Form::label('contact_email',__('messages.email'),['class'=>'']) !!}
                        {!! Form::email('contact_email',(isset($school)?$school['contact_email']:''),['id'=>'contact_email','class'=>'form-control','placeholder'=>'example@example.com','autocomplete'=>'off',"data-parsley-type-message"=>__('messages.email_type'),'data-parsley-required-message'=>__('messages.val_required'),'required']) !!}
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="position-relative form-group">
                        {!! Form::label('contact_phone',__('messages.phone_number'),['class'=>'']) !!}
                        {!! Form::text('contact_phone',(isset($school)?$school['contact_phone']:''),['class'=>'form-control','placeholder'=>'514-528-5895','id'=>'contact_phone','autocomplete'=>'off','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required']) !!}
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <!--<button class="mt-2 btn btn-primary float-right">Edit</button>-->
                     {!! Form::submit(__('messages.submit'), ['name' => 'submit','class'=>'mt-2 btn btn-primary float-right']) !!}
                  </div>
               </div>
               {!! Form::close() !!}
               <div class="row">
                  <div class="col-md-12">
                     <hr>
                     <h5 class="mt-4">List of Associated Teachers</h5>
                     <table class="mb-0 table table-hover">
                     <table id="first" class="mb-0 table table-hover display table-bordered" data-url="{{ route('school_management.teacher_lists') }}">
                        <thead>
                           <tr>
                              <th>Email</th>
                              <th>Last Name</th>
                              <th>First Name</th>
                              <th>Number of Students</th>
                           </tr>
                        </thead>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@stop
@section('page-script')
<script>
$(function(){
   $("#school_form").on('submit', function(e){
      e.preventDefault();
      var form = $(this);

      form.parsley().validate();

      if (form.parsley().isValid()){
         document.createElement('form').submit.call(document.getElementById('school_form'));
      }
   });

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
         var pageVal = true;
         var column = [
            {data: 'email'},
            {data: 'lastname'},
            {data: 'firstname'},
            {data: 'no_of_students'},
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
         ajax:{ url: url,data: function(d) {d.school_id = "{{ $school['id'] }}"; d.LanguageID = lang_id; d._token =  "{{ csrf_token() }}";}},
         columns: column,
         fnDrawCallback: function( oSettings ) { },
            "createdRow": function(row, data, dataIndex){ $('[data-toggle="tooltip"]', row).tooltip(); },
      });
   });
});
</script>
@stop
