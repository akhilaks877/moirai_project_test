@extends('layouts.Student.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
<style>
table td:nth-last-child(3){
    text-align: center;
}
table td:nth-last-child(2)
{
    width:30%
}
</style>
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
                     <a href="index.html">Home</a> > List of Classes
                  </nav>
               </div>
            </div>
         </div>
      </div>
   </div>
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
   <p id="content-new"></p>
   <div class="modal fade" id="view_modal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-header" style="border-bottom:none;">
               <h6 class="title" id="defaultModalLabel">Community - <span class="text-info" id="zone_name"></span></h6>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form id="update_ward_from" data-parsley-validate>
               @csrf
               <div class="modal-body">
                  <div class="row clearfix">
                     <div class="col-md-12">
                        <span id="form_output"></span>
                     </div>
                     <div class="col-lg-12 col-md-12 aob_2">
                        <div class="form-group">
                           <div class="body">
                              <p id="content"></p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
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
                  <input name="search_student" id="search_student" placeholder="Search by class name, teacher, school..." type="text" class="form-control">
                  <div class="input-group-append">
                     <a href="#nogo" class="btn btn-secondary btn-search"><span class="metismenu-icon pe-7s-search"></span></a>
                  </div>
               </div>
               <div class="table-responsive">
               <table class="table table-bordered yajra-datatable dt-responsive" id="CategoryTable" style="table-layout: fixed; width: 100%;">
                  <thead>
                     <tr>
                        <th>Class Name</th>
                        <th>School</th>
                        <th>Teacher</th>
                        <th>Number of Students</th>
                        <th>Read the book</th>
                        <th>Action</th>
                     </tr>
                  </thead>
               </table>
            </div>
               <hr>
            </div>
         </div>
         
         <form data-parsley-validate novalidate id="request_form" method="POST" action="{{ route('student.request') }}">
            @csrf
            <div class="row d-flex">
               <div class="col-md-8">
                  <h5>Choose a class you'd like to be part of</h5>
                  <label for="select_class" class="">Search for a class (search by teacher or class name)</label>
                  <div class="select_class_parent">
                     {!! Form::select('select_class_parent[]', (isset($teacher)?$teacher:[]),'',["style"=>"display:none",'id'=>'select_class_parent','class'=>'form-control','data-parsley-required-message'=>__('messages.val_required'),'data-parsley-required',"data-parsley-errors-container"=>"#class_error","multiple"]) !!}
                     {{-- 
                     <select name="select_class_parent" class="form-control" multiple id="select_class_parent">
                        @if(isset($teachers))
                        @foreach($teachers as $t)
                        <option value="{{$t->classes_id}}">
                           {{$t->class_name.' - '.'taught by'.' - '.$t->full_name}}
                        </option>
                        @endforeach
                        @endif
                     </select>
                     --}}
                  </div>
               </div>
               <div class="col-md-4 align-self-end mt-2">
                  {{-- <a href="#nogo" class="btn btn-primary float-right">
                  
                  Send a request to the teacher
                  </a> --}}
                  {!! Form::submit(__('Send request to teacher'), ['name' => 'submit','class'=>'mt-2 btn btn-primary float-right']) !!}
                  
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
@stop
@section('page-script')
<script>
$(function() {
    $('.select_class_parent').dropdown({
        input: '<input type="text" maxLength="20" placeholder="Search">',
        searchable: true,
        searchNoData: '<li style="color:#ddd">No results found.</li>',
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table1 = $('#CategoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{route("student.my-class")}}',
            type: 'GET',
        },
        columns: [
            {data: 'class_name',name: 'class_name'},
            {data: 'school',name: 'school'},
            {data: 'teacher',name: 'teacher'},
            {data: 'no_of_students',name: 'no_of_students'},
            {data: 'read_book',name: 'read_book'},
            {data: 'action',name: 'action'},
        ],
        fnDrawCallback: function(oSettings) {
           //
           
        },
        "createdRow": function(row, data, dataIndex) {
            $('[data-toggle="tooltip"]', row).tooltip();
        }
    });

    $('#CategoryTable').on('click', '.del_', function(e) {
        e.preventDefault();
        return confirm('fewfe');
    });

    $('#request_form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        form.parsley().validate();
        if (form.parsley().isValid()) {
            document.createElement('form').submit.call(document.getElementById('request_form'));
        }
    });
});
</script>
@stop