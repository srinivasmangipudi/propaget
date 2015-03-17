<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>My Laravel Application</title>

	<link href="{{url('css/app.css')}}" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <script type="text/javascript">

        </script>
	<![endif]-->
</head>
<body>
    @include('nav')

	@yield('content')

    <!--<button id="click">Click</button>-->

	<!-- Scripts -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	 <script>
          var base_url = "{{ URL::to('/') }}/";
    </script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#click').click(function() {
                setAjax();
            });
        });

        function setAjax() {
            $.ajax({
                url: "http://192.168.7.102/propagate/public/register-device",
                async: false,
                type: "POST",
                data: "deviceId=Amitav&registrationId=Roy",
                dataType: "html",
                success: function(data) {
                    console.log(data);
                }
            });
        }
    </script>
    <script src="{{url('js/lib/angular.min.js')}}"></script>
    <script src="{{url('js/lib/angular-route.min.js')}}"></script>
    <script src="{{url('js/prod/propertyController.min.js')}}"></script>
</body>
</html>
