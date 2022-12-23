<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
  <?php
    if (isset($description_for_layout)) {
        echo "<meta name='description' content='" . $description_for_layout . "' />";
    }
    if (isset($keywords_for_layout)) {
        echo "<meta name='keywords' content='" . $keywords_for_layout . "' />";
    }
    if(isset($meta_title_content)) { ?>
        <meta property="og:title" content="<?php echo $meta_title_content; ?>"/>
    <?php } ?>
  <title><?php echo !empty($title_for_layout) ? $title_for_layout:'fundsup'; ?></title>
  <link rel="icon" type="image/x-icon" href="{!! asset('fav.png'); !!}"/>
  <link href="{!! asset('css/loader.css')!!}" rel="stylesheet" type="text/css" />
  <script src="{!! asset('js/loader.js')!!}"></script>

  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
  <link href="{!! asset('css/bootstrap.min.css')!!}" rel="stylesheet" type="text/css" />
  <link href="{!! asset('css/plugins.css')!!}" rel="stylesheet" type="text/css" />
  <link href="{!! asset('css/perfect-scrollbar.css')!!}" rel="stylesheet" type="text/css" />
  <!-- END GLOBAL MANDATORY STYLES -->
</head>
<body class="alt-menu sidebar-noneoverflow">
  <!-- BEGIN LOADER -->
  <div id="load_screen">
    <div class="loader">
      <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
      </div>
    </div>
  </div>
  <!--  END LOADER -->
  <!-- <?php echo config('app.name'); ?>-->
  @include('layouts.admin.header')
  <div class="main-container sidebar-closed sbar-open" id="container">
    <div class="overlay"></div>
    <div class="cs-overlay"></div>
    <div class="search-overlay"></div>
    @include('layouts.admin.sidebar')
    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content">
      <div class="layout-px-spacing">
        <div class="layout-top-spacing">
          @yield('content')
        </div>
      </div>
    </div>
  </div>
  
  <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{!! asset('js/libs/jquery-3.1.1.min.js')!!}"></script>
    <script src="{!! asset('bootstrap/js/popper.min.js')!!}"></script>
    <script src="{!! asset('bootstrap/js/bootstrap.min.js')!!}"></script>
    <script src="{!! asset('js/perfect-scrollbar.min.js')!!}"></script>
    <script src="{!! asset('js/app.js')!!}"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="{!! asset('js/custom.js')!!}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
@section('css')
@show
@section('scripts')
@show
</body>
</html>


