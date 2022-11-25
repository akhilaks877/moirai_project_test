<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Moiraï Publishing Platform</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes, shrink-to-fit=yes" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
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
    <link href="{{ asset('book_template_assets/main.css') }} " rel="stylesheet">
    <link href="{{ asset('book_template_assets/reader.css') }} " rel="stylesheet">
    <link href="{{ asset('book_template_assets/front_teacher_student.css') }}" rel="stylesheet">

</head>

<body>

    <!-- These two links are here to optimize the screenreader users experience -->
    <a class="skip-main just_for_screereaders" tabindex="-1" href="#main_content">Skip to Main Content</a>
    <a class="skip-main just_for_screereaders" tabindex="-1" href="accessibility.html">Change Accessibility Settings</a>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">

        <!-- Header beginning -->
        @include('layouts.Student.header')
        <!-- Header end -->

        <div class="app-main">
            <!-- Sidebar beginning -->
            @include('layouts.Student.sidebar')
            <!-- Sidebar end -->
            <!-- Page content beginning -->
            <div class="app-main__outer" id="main_content">
                @yield('pageContant')
                <!-- Page content end -->
                <!-- footer beginning -->
                @include('layouts.Student.footer')

            </div>
        </div>
    </div>
    <script src=" {{ asset('book_template_assets/assets/scripts/main.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>


    <!-- These scripts and stylesheets only need to be called in the reading context (during reading or completing exercises) -->
    <script src=" {{ asset('book_template_assets/assets/scripts/reader.js') }}"></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

</body>

</html>
