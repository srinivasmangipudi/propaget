<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{$pageTitle or 'Welcome to Propagate'}}</title>
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/bootstrap-theme.min.css') }}
</head>
<body>
    <div class="container">
        @include('layouts.nav')

        @if (Session::get('message'))
            <div class="row"><div class="col-md-12">
                    <div class="alert alert-{{ Session::get('message-flag') }}">{{ Session::get('message') }}</div>
            </div></div>
        @endif

        @yield('content')
    </div>
    <script type="text/javascript" src="{{url('js/jquery-1.11.2.min.js')}}"></script>
    <script type="text/javascript" src="{{url('js/bootstrap.min.js')}}"></script>
</body>
</html>