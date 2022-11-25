@extends('layouts.Student.app')
@section('title', 'Moiraï Administrative Platform')
@section('content')
@section('page-styles')
@stop

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <span class="metismenu-icon pe-7s-users"></span>
                </div>
                <div>
                    <h4 class="mb-0 page-title">List of Students</h4>
                    <span class="subtitle"> - This page shows all students created on the platform.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="index.html">Home</a> > List of Students
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">List of Students</h5>
            <div class="form-row">
                <div class="col-md-12">
                    <label for="search_student" class="">Find a student</label>
                    <div class="input-group mb-4">
                        <input name="search_student" id="search_student" placeholder="Search by first name, last name, class..." type="text" class="form-control">
                        <div class="input-group-append">
                            <a href="#nogo" class="btn btn-secondary"><span class="metismenu-icon pe-7s-search"></span></a>
                        </div>
                    </div>

                    <table class="mb-0 table table-hover">
                        <thead>
                        <tr>
                            <th>Class</th>
                            <th>Action</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($invitation_lists as $list)
                        <tr>
                            <td>{{$list->class_name}}</td>
                           
                            <td>
                                <a href="{{route('student.accept_invitation',['id' => $student_id,'class_id' => $list->class_id])}}">Accept Invitation</a><br />
                               
                            </td>
                           
                        </tr>
                        @endforeach
                   
                      </tbody>
                    </table>
                    <hr>
                </div>
            </div>


           

        </div>
    </div>
</div>
@stop
@section('page-script')
<script>
$(function(){

});
</script>
@stop
