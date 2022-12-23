<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title><?php echo !empty($title_for_layout) ? 'fundsup - '.$title_for_layout:'fundsup - Login'; ?></title>
    <link rel="icon" type="image/x-icon" href="{!! asset('fav.png'); !!}"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700') !!}" rel="stylesheet">
    <link href="{!! asset('css/bootstrap.min.css" rel="stylesheet')!!}" type="text/css" />
    <link href="{!! asset('css/plugins.css" rel="stylesheet')!!}" type="text/css" />
    <link href="{!! asset('css/authentication/form-2.css')!!}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="{!! asset('css/forms/theme-checkbox-radio.css')!!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('css/forms/switches.css')!!}">
</head>
<body class="form">
    <div class="form-container outer">
        <div class="form-form">
            @yield('content')
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{!! asset('js/scripts.js')!!}"></script>
    <script type="text/javascript">
    </script>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{!! asset('js/libs/jquery-3.1.1.min.js')!!}"></script>
    <script src="{!! asset('js/popper.min.js')!!}"></script>
    <script src="{!! asset('js/bootstrap.min.js')!!}"></script>
    
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="{!! asset('js/authentication/form-2.js')!!}"></script>

    @section('scripts')
    @show
</body>
</html>
