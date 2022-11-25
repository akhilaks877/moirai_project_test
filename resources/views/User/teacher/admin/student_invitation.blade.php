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
                    <h4 class="mb-0 page-title">Invite students to [NAME OF THE CLASS]</h4>
                    <span class="subtitle"> - This page allows you to add students to your virtual classes.</span>
                    <div class="page-title-subheading">
                        <nav id="fil-ariane">
                            <a href="index.html">Home</a> > <a href="my-classes_teacher.html">List of Classes</a> > [NAME OF THE CLASS]
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
    <div class="col-sm-12">
        <div class="alert  alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        </div>
    </div>
@endif
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active">
            <div class="main-card mb-3 card">
                <div class="card-body"><h5 class="card-title">Add Students</h5>
                <p>List the email addresses of students you wish to add. These students will receive a link to join your virtual class.</p>
                   <form method="POST" action="{{route('teacher.my-classes.detail-class',['id' => $id])}}">

                   @csrf
                 

                    <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="list_emails" class="">List of Email Addresses</label><textarea id="list_emails" name="text" class="form-control"  rows="4" >
@foreach($students as $student)
{{$student->email}}
@endforeach
  </textarea>
  <small class="form-text text-muted">Only one email address per line.</small>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                               <input type="hidden" name="class_id" value="{{$id}}">
                             
                             <!--  <a class="mt-2 btn btn-primary float-right" href="{{route('teacher.my-classes.detail-class',$id)}}">Send invitations</a> -->
                            <button class="mt-2 btn btn-primary float-right" type="submit" name="submit">Send Invitations</button>
                            <a class="mt-2 btn btn-primary float-right mr-3">Invite students later</a>
                               
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')

@stop
