@extends('layouts.Teacher.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <span class="metismenu-icon pe-7s-display1 icon-gradient bg-green"></span>
                </div>
                <div>
                    <h4 class="mb-0 page-title">List of Classes</h4>
                    <span class="subtitle"> - This page shows all your classes.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="{{ route('teacher.dashboard.index') }}">Home</a> > List of Classes
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">List of Classes</h5>
            <div class="form-row">
                <div class="col-md-12">
                    <label for="search_student" class="">Find a Class</label>
                    <div class="input-group mb-4">
                        <input name="search_student" id="search_student" placeholder="Search by class name, student, school..." type="text" class="form-control">
                    </div>

                    <table class="mb-0 table table-hover" data-url="{{ route('teacher.my-classes.index') }}" id="myClasslist" style="width:100%;">
                        <thead>
                        <tr>
                            <th>Class Name</th>
                            <th>School</th>
                            <th>Number of Students</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        {{-- <tbody>
                        <tr>
                            <td>Class 1 lorem ipsum</td>
                            <td>Name of School</td>
                            <td>
                                21
                            </td>
                            <td>
                                <a href="detail_class.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-eye pr-2"></span> Class Details</a>
                                <a href="propose_book.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-book pr-2"></span> Suggest a Book</a>
                            </td>
                        </tr>
                        </tbody> --}}
                    </table>
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <a href="{{ route('teacher.my-classes.create') }}" class="btn btn-primary float-right">
                    <span class="fa fa-plus"></span>
                    Add a Class
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script>
$(function(){
    var table=$("#myClasslist").DataTable({
        info: true,
        ordering: false,
        dom: "rtp",
        serverSide: true,
        searching: true,
        ajax: {
         url: $(this).data('url'),
         type: "get",
         data: function(d) {
            d._token = '{{ csrf_token() }}';
          }
        },
        columns: [
            { data: 'class_name', name: 'class_name' },
            { data: 'school_name', name: 'school_name' },
            { data: 'num_students', name: 'num_students' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        fnDrawCallback: function( oSettings ){},
        "createdRow": function(row, data, dataIndex){},
     });
     $('#search_student').on( 'keyup click', function () {
		$("#myClasslist").DataTable().search( $('#search_student').val()).draw();
		});
});
</script>
@stop
