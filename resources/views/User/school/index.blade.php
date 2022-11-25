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
table#first td:nth-child(1),th:nth-child(1),
td:nth-last-child(1),th:nth-last-child(1),
td:nth-last-child(2),th:nth-last-child(2),
td:nth-last-child(3),th:nth-last-child(3)
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
                    <span class="metismenu-icon pe-7s-study"></span>
                </div>
                <div>
                    <h4 class="mb-0 page-title">{{__('messages.list_of_schools')}}</h4>
                    <span class="subtitle"> - {{__('messages.this_page_will_school')}}</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('admin.dashboard') }}">{{__('messages.home')}}</a> > {{__('messages.list_of_schools')}}
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
                <h5 class="card-title">@lang_u('messages.list_of_schools')</h5>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <table id="first" class="mb-0 table table-hover display table-hover table-bordered" data-url="{{ route('school_management.index') }}">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('messages.school_name')}}</th>
                                    <th>{{__('messages.no_of_teachers')}}</th>
                                    <th>{{__('messages.no_of_students')}}</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <a href="{{ route('school_management.create') }}" class="btn btn-primary float-right">
                                <span class="fa fa-plus"></span>
                                 {{__('messages.add_a_school')}}
                            </a>
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

    var table = '';
    $('.display').each(function()
    {
        var lang_id = $(this).attr('id');
        var url = $(this).data('url');
        var column = [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'school_name'},
            {data: 'teachers_count'},
            {data: 'students_count'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
        table = $("#" + lang_id).DataTable({
            processing: true,
            paging: true,
			ordering: false,
            info: false,
            dom: "rtp",
			language: {  "emptyTable": "{{__('messages.no_data_table')}}",paginate: { next: '&raquo;', previous: '&laquo;' } },
            serverSide: true,
            searching: false,
            ajax:{ url: url,data: function(d) {d.LanguageID = lang_id;d._token =  "{{ csrf_token() }}";}},
            columns: column,
			fnDrawCallback: function( oSettings ) {
				//delete
				$('.editModal').on('click', function(e) {
					e.preventDefault();
					if (confirm("Are you sure?")) {
						var id = $(this).data('id');
						var url = "{{ route('school_management.destroy', ":id") }}";
						url = url.replace(':id', id);
						if (id) {
							$.ajax({
							 type: 'DELETE',
							 url: url,
							 data: {
								'_token': $('meta[name=csrf-token]').attr('content'),
							 },
							 dataType: 'json',
							 success: function(response) {
								if (response.status === 'success') {
								   ///console.log(response);
								   alert('Success');
								   $("#" + lang_id).DataTable().ajax.reload();
								}
							 }
						  });
						}
					}
					return false;

				});
				//delete
			},
			"createdRow": function(row, data, dataIndex){ $('[data-toggle="tooltip"]', row).tooltip(); },
			});
    });

});
</script>
@stop
