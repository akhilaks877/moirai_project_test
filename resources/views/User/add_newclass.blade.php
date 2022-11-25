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
                    <h4 class="mb-0 page-title">Class Account: CLASSNAME</h4>
                    <span class="subtitle"> - This page shows the information for the class CLASSNAME.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="index.html">Home</a> > <a href="classes.html">List of Classes</a> > CLASSNAME
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
                                    Class created on: 28/03/2020 at 18:34<br />
                                    By: <a href="#">Loïc Marin (Admin)</a>
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
                                    <label for="class_name" class="">Class Name</label>
                                    <input name="class_name" id="class_name" placeholder="Class Name" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="select_school" class="">School</label>
                                    <div class="select_school_parent">
                                      <select style="display:none"  name="select_school" id="select_school">
                                        <option value="School 1">School 1</option>
                                        <option value="School 2">School 2</option>
                                        <option value="School 3">School 3</option>
                                        <option value="School 4">School 4</option>
                                      </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="select_teacher" class="">Teacher</label>
                                    <div class="select_teacher_parent">
                                      <select style="display:none"  name="select_teacher" id="select_teacher">
                                        <option value="Teacher 1 First name and last name">Teacher 1 First name and last name</option>
                                        <option value="Teacher 2 First name and last name">Teacher 2 First name and last name</option>
                                        <option value="Teacher 3 First name and last name">Teacher 3 First name and last name</option>
                                        <option value="Teacher 4 First name and last name">Teacher 4 First name and last name</option>
                                      </select>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-6">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-check">
                                    <input type="checkbox" class="form-check-input" id="display_answer">
                                    <label class="form-check-label" for="display_answer">Enable answer display: Each student in this class will automatically see the exercise answers after submission.</label>
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
                        <div class="col-md-12 mb-4">
                            <hr>
                            <h5 class="mt-4">List of Students in the Class (8)</h5>
                            <table class="mb-0 table table-hover">
                                <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>student@student.com</td>
                                    <td>Doe</td>
                                    <td>John</td>
                                    <td>
                                        <a href="detail_student.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>student@student.com</td>
                                    <td>Doe</td>
                                    <td>John</td>
                                    <td>
                                        <a href="detail_student.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>student@student.com</td>
                                    <td>Doe</td>
                                    <td>John</td>
                                    <td>
                                        <a href="detail_student.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>student@student.com</td>
                                    <td>Doe</td>
                                    <td>John</td>
                                    <td>
                                        <a href="detail_student.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>student@student.com</td>
                                    <td>John</td>
                                    <td>Doe</td>
                                    <td>
                                        <a href="detail_student.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>student@student.com</td>
                                    <td>Doe</td>
                                    <td>John</td>
                                    <td>
                                        <a href="detail_student.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>student@student.com</td>
                                    <td>Doe</td>
                                    <td>John</td>
                                    <td>
                                        <a href="detail_student.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>student@student.com</td>
                                    <td>Doe</td>
                                    <td>John</td>
                                    <td>
                                        <a href="detail_student.html" class="mb-2 mr-2 border-0 btn-transition btn btn-outline-info"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row d-flex">
                        <div class="col-md-8">
                            <h5>Choose students to add to the class</h5>
                            <label for="select_student" class="">Choose student(s)</label>
                            <div class="select_student_parent">
                              <select style="display:none"  name="select_student" multiple id="select_student">
                                <option value="Bob dylan">Bob Dylan</option>
                                <option value="John Doe">John Doe</option>
                                <option value="Jolly Jumper">Jolly Jumper</option>
                                <option value="Juan Carlos">Juan Carlos</option>
                                <option value="Isabelle the second">Isabelle the Second</option>
                                <option value="Jack Bauer">Jack Bauer</option>
                              </select>
                            </div>
                        </div>

                        <div class="col-md-4 align-self-end mt-2">
                            <a href="detail_student.html" class="btn btn-primary float-right">
                                <span class="fa fa-plus"></span>
                                Add new students to the class
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
    $('.select_student_parent').dropdown({
	input:'<input type="text" maxLength="20" placeholder="Search">',
	searchable:true,
	searchNoData: '<li style="color:#ddd">No results found.</li>',
});

$('.select_school_parent').dropdown({
	input:'<input type="text" maxLength="20" placeholder="Search">',
	searchable:true,
	searchNoData: '<li style="color:#ddd">No results found.</li>',
});

$('.select_teacher_parent').dropdown({
	input:'<input type="text" maxLength="20" placeholder="Search">',
	searchable:true,
	searchNoData: '<li style="color:#ddd">No results found.</li>',
});
});
</script>
@stop
