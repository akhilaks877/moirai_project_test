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
                    <span class="metismenu-icon pe-7s-study"></span>
                </div>
                <div>
                    <h4 class="mb-0 page-title">List of Schools</h4>
                    <span class="subtitle"> - This page allows the management of all schools on the platform.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="index.html">Home</a> > List of Schools
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active">
            <div class="main-card mb-3 card">
                <div class="card-body">
                <h5 class="card-title">List of Schools</h5>
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <table class="mb-0 table table-hover">
                                <thead>
                                <tr>
                                    <th>School Name</th>
                                    <th>Number of Teachers</th>
                                    <th>Number of Students</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>School name</td>
                                    <td>1</td>
                                    <td>0</td>
                                    <td>
                                        <a href="detail_ecole.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info "><span class="fa fa-pen"></span></a>
                                        <a href="#" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-dark disabled"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>School name</td>
                                    <td>1</td>
                                    <td>15</td>
                                    <td>
                                        <a href="detail_ecole.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info "><span class="fa fa-pen"></span></a>
                                        <a href="#" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-dark disabled"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>School name</td>
                                    <td>0</td>
                                    <td>0</td>
                                    <td>
                                        <a href="detail_ecole.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info "><span class="fa fa-pen"></span></a>
                                        <a href="#" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info "><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>School name</td>
                                    <td>3</td>
                                    <td>42</td>
                                    <td>
                                        <a href="detail_ecole.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info "><span class="fa fa-pen"></span></a>
                                        <a href="#" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-dark disabled"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
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
                        <div class="col-12 mt-2">
                            <a href="{{ route('admin.school_management.add') }}" class="btn btn-primary float-right">
                                <span class="fa fa-plus"></span>
                                Add a school
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
});
</script>
@stop
