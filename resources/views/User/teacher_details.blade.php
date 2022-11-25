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
                    <span class="metismenu-icon pe-7s-portfolio"></span>
                </div>
                <div>
                    <h4 class="mb-0 page-title">List of teachers created on the platform.</h4>
                    <span class="subtitle"> - This page shows all teachers created on the platform.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="index.html">Home</a> > List of Teachers
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">List of Teachers</h5>
            <div class="form-row">
                <div class="col-md-12">

                    <label for="search_teacher" class="">Find a teacher</label>
                    <div class="input-group mb-4">
                        <input name="search_teacher" id="search_teacher" placeholder="Search by first name, last name, class..." type="text" class="form-control">
                        <div class="input-group-append">
                            <a href="#nogo" class="btn btn-secondary"><span class="metismenu-icon pe-7s-search"></span></a>
                        </div>
                    </div>

                    <table class="mb-0 table table-hover">
                        <thead>
                        <tr>
                            <th>Email</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Class(es)</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>teacher@teacher.com</td>
                            <td>Doe</td>
                            <td>John</td>
                            <td>
                                <a href="detail_class.html">Class 1 (10 students)</a><br />
                                <a href="detail_class.html">Class 2 (21 students)</a><br />
                            </td>
                            <td>
                                <a href="detail_teacher.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>
                            </td>
                        </tr>
                        <tr>
                            <td>teacher@teacher.com</td>
                            <td>Doe</td>
                            <td>John</td>
                            <td>
                                <a href="detail_classe.html">Class 3 (8 students)</a><br />
                            </td>
                            <td>
                                <a href="detail_teacher.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>
                            </td>
                        </tr>
                        <tr>
                            <td>teacher@teacher.com</td>
                            <td>Doe</td>
                            <td>John</td>
                            <td>
                                <a href="detail_classe.html">Class 5 (14 students)</a><br />
                                <a href="detail_classe.html">Class 6 (12 students)</a><br />
                            </td>
                            <td>
                                <a href="detail_teacher.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>
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
                    <a href="{{ route('admin.teacher_details.add') }}" class="btn btn-primary float-right">
                    <span class="fa fa-plus"></span>
                    Add a teacher
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
