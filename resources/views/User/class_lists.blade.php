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
                    <h4 class="mb-0 page-title">List of classes created on the platform</h4>
                    <span class="subtitle"> - This page shows all classes created by administrators or teachers.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="index.html">Home</a> > List of Classes
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
                    <label for="search_student" class="">Find a class</label>
                    <div class="input-group mb-4">
                        <input name="search_student" id="search_student" placeholder="Search by class name, teacher, school..." type="text" class="form-control">
                        <div class="input-group-append">
                            <a href="#nogo" class="btn btn-secondary"><span class="metismenu-icon pe-7s-search"></span></a>
                        </div>
                    </div>

                    <table class="mb-0 table table-hover">
                        <thead>
                        <tr>
                            <th>Class Name</th>
                            <th>Created by</th>
                            <th>School</th>
                            <th>Number of students</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Class 1 lorem ipsum</td>
                            <td><a href="detail_teacher.html">Name of teacher (Teacher)</a></td>
                            <td>Name of school</td>
                            <td>
                                21
                            </td>
                            <td>
                                <a href="detail_class.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>
                            </td>
                        </tr>
                        <tr>
                            <td>Class 2 lorem ipsum</td>
                            <td><a href="detail_admin.html">Loïc Marin (Admin)</a></td>
                            <td>Name of school</td>
                            <td>
                                12
                            </td>
                            <td>
                                <a href="detail_class.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>
                            </td>
                        </tr>
                        <tr>
                            <td>Class 3 lorem ipsum</td>
                            <td><a href="detail_teacher.html">Name of teacher (Teacher)</a></td>
                            <td>Name of school</td>
                            <td>
                                26
                            </td>
                            <td>
                                <a href="detail_class.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <nav class="d-flex" aria-label="Page navigation example">
                        <ul class="pagination mx-auto">
                            <li class="page-item"><a href="javascript:void(0);" class="page-link" aria-label="Previous"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>
                            <li class="page-item"><a href="javascript:void(0);" class="page-link">1</a></li>
                            <li class="page-item active"><a href="javascript:void(0);" class="page-link">2</a></li>
                            <li class="page-item"><a href="javascript:void(0);" class="page-link">3</a></li>
                            <li class="page-item"><a href="javascript:void(0);" class="page-link">4</a></li>
                            <li class="page-item"><a href="javascript:void(0);" class="page-link">5</a></li>
                            <li class="page-item"><a href="javascript:void(0);" class="page-link" aria-label="Next"><span aria-hidden="true">»</span><span class="sr-only">Next</span></a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <a href="{{ route('admin.class_management.add') }}" class="btn btn-primary float-right">
                    <span class="fa fa-plus"></span>
                    Add a class
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
});
</script>
@stop
