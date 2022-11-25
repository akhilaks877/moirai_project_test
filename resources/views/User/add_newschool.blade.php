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

                    <h4 class="mb-0 page-title">School Account: SCHOOLNAME</h4>
                    <span class="subtitle"> - This page shows the information for the school SCHOOLNAME.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="index.html">Home</a> > <a href="schools.html">List of Schools</a> > SCHOOLNAME
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
                    <h5 class="card-title">General Information</h5>
                    <form class="">
                        <div class="form-row">
                            <div class="col-md-6">
                                <p>
                                    School created on: 28/03/2020 at 18:34<br />
                                    By: Frédéric Raguenez (Admin)
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
                                    <label for="school_name" class="">School Name</label>
                                    <input name="school_name" id="school_name" placeholder="School name" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="school_street" class="">Address</label>
                                    <input name="school_street" id="school_street" placeholder="2200 Sample Road" type="password" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="school_city" class="">City</label>
                                    <input name="school_city" id="school_city" placeholder="Montreal" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="school_zipcode" class="">Postal Code</label>
                                    <input name="school_zipcode" id="school_zipcode" placeholder="H2H 2H2" type="text" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="school_province" class="">Province</label>
                                    <input name="school_province" id="school_province" placeholder="Quebec" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="school_country" class="">Country</label>
                                    <input name="school_country" id="school_country" placeholder="Canada" type="text" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="card-title">Contact Person</h5>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="contact_last_name" class="">Last Name</label>
                                    <input name="contact_last_name" id="contact_last_name" placeholder="Doe" type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="contact_first_name" class="">First Name</label>
                                    <input name="contact_first_name" id="contact_first_name" placeholder="John" type="text" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="contact_email" class="">Email</label>
                                    <input name="contact_email" id="contact_email" placeholder="example@example.com" type="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="contact_phone" class="">Phone Number</label>
                                    <input name="contact_phone" id="contact_phone" placeholder="514-528-5895" type="text" class="form-control" required>
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
                            <h5 class="mt-4">List of Associated Teachers</h5>
                            <table class="mb-0 table table-hover">
                                <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Number of Students</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><a href="detail_enseignant.html">teacher@teacher.com</a></td>
                                    <td><a href="detail_enseignant.html">Doe</a></td>
                                    <td><a href="detail_enseignant.html">John</a></td>
                                    <td>12</td>
                                </tr>
                                <tr>
                                    <td><a href="detail_enseignant.html">teacher@teacher.com</a></td>
                                    <td><a href="detail_enseignant.html">Doe</a></td>
                                    <td><a href="detail_enseignant.html">John</a></td>
                                    <td>4</td>
                                </tr>
                                <tr>
                                    <td><a href="detail_enseignant.html">teacher@teacher.com</a></td>
                                    <td><a href="detail_enseignant.html">Doe</a></td>
                                    <td><a href="detail_enseignant.html">John</a></td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td><a href="detail_enseignant.html">teacher@teacher.com</a></td>
                                    <td><a href="detail_enseignant.html">Doe</a></td>
                                    <td><a href="detail_enseignant.html">John</a></td>
                                    <td>8</td>
                                </tr>
                                <tr>
                                    <td><a href="detail_enseignant.html">teacher@teacher.com</a></td>
                                    <td><a href="detail_enseignant.html">Doe</a></td>
                                    <td><a href="detail_enseignant.html">John</a></td>
                                    <td>16</td>
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
});
</script>
@stop
