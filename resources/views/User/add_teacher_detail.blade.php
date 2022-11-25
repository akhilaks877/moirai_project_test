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
                    <span class="metismenu-icon pe-7s-note2"></span>
                </div>
                <div>
                    <h4 class="mb-0 page-title">Teacher Account: LASTNAME, FIRSTNAME</h4>
                    <span class="subtitle"> - This page shows the information for the teacher LASTNAME, FIRSTNAME.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="index.html">Home</a> > <a href="teachers.html">List of Teachers</a> > LASTNAME, FIRSTNAME
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active">
            <div class="main-card mb-3 card">
                <div class="card-body"><h5 class="card-title">General Information</h5>
                    <form class="">
                        <div class="form-row">
                            <div class="col-md-6">
                                <p>
                                    User created on: 28/03/2020 at 18:34<br />
                                    By: Magento Shop
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p>
                                    Last modified on: 29/03/2020 at 13:15<br />
                                    By: <a href="#">Loïc Marin (Admin)</a>
                                </p>
                            </div>
                            <div class="col-md-12">
                                <hr>
                            </div>

                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">Email</label>
                                    <input name="email" id="exampleEmail11" placeholder="example@example.com" type="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="examplePassword11" class="">Password</label>
                                    <input name="password" id="examplePassword11" placeholder="**********" type="password" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="last_name" class="">Last Name</label>
                                    <input name="last_name" id="last_name" placeholder="Doe" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="first_name" class="">First Name</label>
                                    <input name="first_name" id="first_name" placeholder="John" type="text" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-3">
                                <img src="{{ asset('assets/images/avatars/placeholder_profile.jpg') }}" alt="Your current profile picture" title="Your current profile picture" style="width: 100%" />
                            </div>
                            <div class="col-md-3">
                                <label for="exampleFile">Profile Picture</label>
                                <input name="file" id="exampleFile" type="file" class="form-control-file">
                                <small class="form-text text-muted">Recommended size: 400x400 pixels. Max file size: 2 MB</small>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="id_magento" class="">Magento ID</label>
                                    <input name="id_magento" id="id_magento" placeholder="215485" type="text" class="form-control" required>
                                    <a href="#">See the Magento Customer Main File</a>
                                </div>
                                <div class="position-relative form-group">
                                    <label for="langue" class="">Preferred Language</label>
                                        <select name="langue" id="langue" class="form-control">
                                            <option>Français</option>
                                            <option>English</option>
                                        </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="mt-2 btn btn-primary float-right">Edit</button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <h5 class="mt-4">List of Classes</h5>
                            <table class="mb-0 table table-hover">
                                <thead>
                                <tr>
                                    <th>Class Name</th>
                                    <th>Teacher</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Class 1</td>
                                    <td><a href="detail_teacher.html">Teacher First and Last Name</a></td>
                                    <td>
                                        <a href="detail_class.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-pen"></span></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <h5 class="mt-4">List of Associated Students</h5>
                            <table class="mb-0 table table-hover">
                                <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Class(es)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><a href="detail_student.html">student@student.com</a></td>
                                    <td><a href="detail_student.html">Doe</a></td>
                                    <td><a href="detail_student.html">John</a></td>
                                    <td><a href="detail_class.html">Class 1</a></td>
                                </tr>
                                <tr>
                                    <td><a href="detail_student.html">student@student.com</a></td>
                                    <td><a href="detail_student.html">Doe</a></td>
                                    <td><a href="detail_student.html">John</a></td>
                                    <td><a href="detail_class.html">Class 1</a></td>
                                </tr>
                                <tr>
                                    <td><a href="detail_student.html">student@student.com</a></td>
                                    <td><a href="detail_student.html">Doe</a></td>
                                    <td><a href="detail_student.html">John</a></td>
                                    <td><a href="detail_class.html">Class 1</a></td>
                                </tr>
                                <tr>
                                    <td><a href="detail_student.html">student@student.com</a></td>
                                    <td><a href="detail_student.html">Doe</a></td>
                                    <td><a href="detail_student.html">John</a></td>
                                    <td><a href="detail_class.html">Class 1</a></td>
                                </tr>
                                <tr>
                                    <td><a href="detail_student.html">student@student.com</a></td>
                                    <td><a href="detail_student.html">Doe</a></td>
                                    <td><a href="detail_student.html">John</a></td>
                                    <td><a href="detail_class.html">Class 1</a></td>
                                </tr>
                                <tr>
                                    <td><a href="detail_student.html">student@student.com</a></td>
                                    <td><a href="detail_student.html">Doe</a></td>
                                    <td><a href="detail_student.html">John</a></td>
                                    <td><a href="detail_class.html">Class 1</a></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mt-2">
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
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script>
$(function(){
    $('.select_class_parent').dropdown({
	input:'<input type="text" maxLength="20" placeholder="Search">',
	searchable:true,
 	searchNoData: '<li style="color:#ddd">No results found.</li>',
  });
});
</script>
@stop
