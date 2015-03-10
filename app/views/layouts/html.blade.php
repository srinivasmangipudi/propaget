<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{$pageTitle or 'Welcome to Propagate'}}</title>
    <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}"/>
    <link rel="stylesheet" href="{{url('css/bootstrap-theme.min.css')}}"/>
</head>
<body>
    <div class="container">
        @include('layouts.nav')
        @yield('content')
    </div>
    <script type="text/javascript" src="{{url('js/jquery-1.11.2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('js/bootstrap.min.js')}}"></script>
</body>
</html>