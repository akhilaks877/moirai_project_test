<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>Moiraï Administrative Platform</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes, shrink-to-fit=yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/scripts/main.js') }}"></script>
    @if (trim($__env->yieldContent('page-styles')))
    @yield('page-styles')
    @endif
</head>
<body>
@yield('content')
</body>
@if(trim($__env->yieldContent('page-script')))
@yield('page-script')
@endif
</html>
