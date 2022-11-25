@extends('layouts.Teacher.app')
@section('title', 'Moiraï Publishing Platform')
@section('content')
@section('page-styles')
@stop
<div class="app-main__inner">
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div>
                    <h4 class="page-title">Welcome to the Moiraï Publishing platform for teachers.</h4>
                    <div class="page-title-subheading">
                        This platform is intended for teachers using Moiraï Publishing and allows them to:
                        <ul>
                            <li>Lorem ipsum dolor sit amet</li>
                            <li>Lorem ipsum dolor sit amet</li>
                            <li>Lorem ipsum dolor sit amet</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <h5 class="card-title">What would you like to do today?</h5>

            <div class="row nav_index">

                <div class="col-6 col-md-4 col-lg-3 text-center px-5 py-3">
                    <a href="{{ route('teacher.title.index') }}" class="">
                        <div class="button_square">
                            <div class="content">
                                <div class="bg" style="background-image: url({{ asset('assets/images/buttons_home/lire_livre.png') }})">
                                </div>
                            </div>
                        </div>
                        <h5 class="pt-3">Read a Book</h5>
                    </a>
                </div>

                <div class="col-6 col-md-4 col-lg-3 text-center px-5 py-3">
                    <a href="{{ route('teacher.my-classes.index') }}" class="">
                        <div class="button_square">
                            <div class="content">
                                <div class="bg" style="background-image: url({{ asset('assets/images/buttons_home/gerer_classes.png') }})">
                                </div>
                            </div>
                        </div>
                        <h5 class="pt-3">Manage Classes</h5>
                    </a>
                </div>

                <div class="col-6 col-md-4 col-lg-3 text-center px-5 py-3">
                    <a href="{{route('teacher.student-request')}}" class="">
                        <div class="button_square">
                            <div class="content">
                                <div class="bg" style="background-image: url({{ asset('assets/images/buttons_home/gerer_eleves.png') }})">
                                </div>
                            </div>
                        </div>
                        <h5 class="pt-3">Manage Students</h5>
                    </a>
                </div>

                <div class="col-6 col-md-4 col-lg-3 text-center px-5 py-3">
                    <a href="{{route('teacher.statistics-teacher')}}" class="">
                        <div class="button_square">
                            <div class="content">
                                <div class="bg" style="background-image: url({{ asset('assets/images/buttons_home/voir_stats.png') }})">
                                </div>
                            </div>
                        </div>
                        <h5 class="pt-3">See Statistics</h5>
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
