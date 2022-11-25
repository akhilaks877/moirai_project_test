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
table#first td:nth-child(1),th:nth-child(1) {
	width: 5%;
}
table#first td:nth-last-child(1),th:nth-last-child(1),
td:nth-last-child(2),th:nth-last-child(2),
td:nth-child(1),th:nth-child(1)
{
	text-align: center;
}
</style>
@stop
<div class="app-main__inner">
   <div class="app-page-title">
      <div class="page-title-wrapper">
         <div class="page-title-heading">
            <div class="page-title-icon">
               <span class="metismenu-icon pe-7s-portfolio"></span>
            </div>
            <div>
               <h4 class="mb-0 page-title">{{__('messages.list_of_teachers_created_on_this_platform')}}.</h4>
               <span class="subtitle"> - {{__('messages.this_page_will_teacher')}}</span>
               <div class="page-title-subheading">
                  <nav id="fil-ariane">
                     <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> > <a href="javascript:;">@lang_u('messages.list_teacher')</a>
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('layouts.Admin.messages')
   <div class="main-card mb-3 card">
      <div class="card-body">
         <h5 class="card-title">{{__('messages.list_of_teacher')}}</h5>
         <div class="form-row">
            <div class="col-md-12">
            <div class="table-responsive">
               <label for="search_teacher" class="">{{__('messages.find_a_teacher')}}</label>
               <div class="input-group mb-4">
                  <input name="search_teacher" id="search_teacher" placeholder="{{__('messages.search_by')}}" type="text" class="form-control">
                  <!-- <div class="input-group-append">
                     <a href="#nogo" class="btn btn-secondary"><span class="metismenu-icon pe-7s-search"></span></a>
                  </div> -->
               </div>

               <table id="first" class="mb-0 table table-hover display table-hover table-bordered" data-url="{{ route('teacher_details.index') }}" >
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>{{__('messages.email')}}</th>
                        <th>{{__('messages.lastname')}}</th>
                        <th>{{__('messages.firstname')}}</th>
                        <th>{{__('messages.classes')}}</th>
                        <th>Action</th>
                     </tr>
                  </thead>
               </table>
               </div>
               <hr>
            </div>
         </div>
         <div class="row">
            <div class="col-lg-12">
               <a href="{{ route('teacher_details.create') }}" class="btn btn-primary float-right">
               <span class="fa fa-plus"></span> {{__('messages.add_a_teacher')}}</a>
            </div>
         </div>
      </div>
   </div>
</div>
@stop
@section('page-script')
<script>
$(function(){
	$.fn.dataTable.ext.errMode = 'throw';
    $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) {
        alert('Please reload the page!');
    };
    var oTable = '';
    $('.display').each(function()
    {
        var lang_id = $(this).attr('id');
        var url = $(this).data('url');
        var column = [
            {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
            {data: 'email'},
            {data: 'last_name'},
			{data: 'first_name'},
            {data: 'classes'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
        oTable = $("#" + lang_id).DataTable({
            processing: true,
            paging: true,
            info: true,
            ordering: false,
            dom: "rtp",
            language: {  "emptyTable": "{{__('messages.no_data_table')}}",paginate: { next: '&raquo;', previous: '&laquo;' } },
            serverSide: true,
            searching: true,
            ajax:{ url: url,data: function(d) {d.LanguageID = lang_id;d._token =  "{{ csrf_token() }}";}},
            columns: column,
            fnDrawCallback: function( oSettings ) { },
               "createdRow": function(row, data, dataIndex){ $('[data-toggle="tooltip"]', row).tooltip(); },
            });
            $('#search_teacher').on( 'keyup click', function () {$("#" + lang_id).DataTable().search( $('#search_teacher').val()).draw();});
    });
});
</script>
@stop
