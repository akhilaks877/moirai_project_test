@extends('layouts.teacher.app')
@section('title', 'Student Requests')
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
	text-align: center;
	width: 5%;
}

</style>
@stop

@if (session()->has('success'))
<div class="alert alert-success">
    @if(is_array(session('success')))
        <ul>
            @foreach (session('success') as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    @else
        {{ session('success') }}
    @endif
</div>
@endif

<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <span class="metismenu-icon pe-7s-id"></span>
                </div>
                <div>
                    <h4 class="mb-0 page-title">{{__('messages.list_of_student_request')}}</h4>
                    <span class="subtitle"> - {{__('messages.this_page_shows_request')}}</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('teacher.dashboard.index') }}">{{__('messages.home')}}</a> > {{__('messages.list_of_student_requests')}}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
	 @include('layouts.teacher.messages')
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active">
            <div class="main-card mb-3 card">
                <div class="card-body">
                <h5 class="card-title">@lang_u('messages.list_of_requests')</h5>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <table id="first" class="mb-0 table table-hover display table-hover table-bordered" data-url="{{ route('teacher.student-request') }}">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('messages.student')}}</th>
                                    <th>{{__('messages.class_requested')}}</th>
                                    <th>{{__('messages.date')}}</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-12 mt-2">
                            <a href="{{ route('admins.create') }}" class="btn btn-primary float-right">
                                <span class="fa fa-plus"></span>
                                 {{__('messages.add_an_administrator')}}
                            </a>
                        </div>
                    </div> --}}
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
            {data: 'student_name'},
            {data: 'class_name'},
            {data: 'date'},
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

                $('.select-class').on('click',function()
                    {
                        console.log("kkk");
                        var id = $(this).data('id');
                        console.log(id);
                        var selected = $(this).val();
                        console.log(selected);
                            $.ajax({
                                type: 'POST',
                                url: '{!! route('teacher.edit-request') !!}',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "id": id,
                                    'selected': selected,
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status == 'accept-request') {
                                        toastr.options = {
                                            "timeOut": "5000",
                                            'positionClass': 'toast-bottom-right'
                                        };
                                        toastr['success']('Student has been added to the class successfully', '');

                                        $('.data-table').DataTable().ajax.reload();
                                    } else {
                                        if (response.status == 'reject-request') {
                                        toastr.options = {
                                            "timeOut": "5000",
                                            'positionClass': 'toast-bottom-right'
                                        };
                                        toastr['success']('Student request has been rejected', '');
                                        $('.data-table').DataTable().ajax.reload();
                                    } else{

                                        toastr.options = {
                                            "timeOut": "5000",
                                            'positionClass': 'toast-bottom-right'
                                        };
                                        toastr['error']('Oops..Please try again Later !', '');
                                        $('.data-table').DataTable().ajax.reload();
                                    }
                                    }
                                }
                            });
                            $('.display').DataTable().ajax.reload(null, false);

                       
                    });

                   


			},
			"createdRow": function(row, data, dataIndex){ $('[data-toggle="tooltip"]', row).tooltip(); },
			});
            
    });

    

});
</script>
@stop
