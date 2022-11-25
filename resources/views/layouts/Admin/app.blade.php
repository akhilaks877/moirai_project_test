<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes, shrink-to-fit=yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
    <title>@yield('title')</title>
    <!--
    =========================================================
    * ArchitectUI HTML Theme Dashboard - v1.0.0
    =========================================================
    * Product Page: https://dashboardpack.com
    * Copyright 2019 DashboardPack (https://dashboardpack.com)
    * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
    =========================================================
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    @if(Request::segment(3)==='manage-note')
    <link href="{{ asset('assets/css/reader.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
    @endif
    @if(Request::segment(3)==='manage-webcontent' || Request::segment(3)==='add_student' || Request::segment(3)==='add_class' || Request::segment(3)==='add_teacher' || Request::segment(3)==='add_school')
    <link href="{{ asset('assets/css/jquery.dropdown.min.css') }}" rel="stylesheet">
    @endif
    @if(Request::segment(2)==='users')
		<link href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
		<link href="{{ asset('assets/css/jquery.dropdown.min.css') }}" rel="stylesheet">
    @endif
    @if(Request::segment(2)==='accessibility')
    <link href="{{ asset('assets/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
    @endif
    @if (trim($__env->yieldContent('page-styles')))
    @yield('page-styles')
    @endif
</head>
<body>

<!-- These two links are here to optimize the screenreader users experience -->
<a class="skip-main just_for_screereaders" tabindex="-1" href="#main_content">Skip to Main Content</a>
<a class="skip-main just_for_screereaders" tabindex="-1" href="accessibility.html">Change Accessibility Settings</a>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header" id="app">

		@include('layouts.Admin.header')
        <div class="app-main">
        @include('layouts.Admin.sidebar')
		<div class="app-main__outer" id="main_content">
             <!-- Page content beginning -->
             @yield('content')
			 <!-- Page content end -->
            @include('layouts.Admin.footer')
		</div>
        </div>
    </div>
</body>
{{-- <script src="{{ asset('js/app.js') }}"></script> --}}<!-- Temporarily commented -->
<script src="{{ asset('js/all.js') }}"></script>
<script src="{{ asset('assets/scripts/main.js') }}"></script>
@if(Request::segment(3)==='manage-note')
<script src="{{ asset('assets/scripts/reader.js') }}"></script>
@endif
@if(Request::segment(3)==='manage-webcontent' || Request::segment(3)==='manage-note')
<script src="https://cdn.tiny.cloud/1/a8bwzvj75bpe5ab85uhrj337bocdwf0hptnqf2i8sxg4v5d3/tinymce/5/tinymce.min.js" referrerpolicy="origin"/></script>
@endif
@if(trim($__env->yieldContent('page-script')))
@yield('page-script')
@endif
<script>
$(function(){ //grayscale
	var img = $('img');
	var class_type = "{{ $image_type }}";
    class_type != null ? img.addClass(class_type) : img.addClass("");
});
</script>
</html>
