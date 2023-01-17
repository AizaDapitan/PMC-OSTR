<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <!-- Meta -->
  <meta name="description" content="" />
  <meta name="author" />

  <!-- CSRF Token -->

  <title>Philsaga - Online Stock Transfer Request System</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{env('APP_URL')}}/assets/img/favicon.png" />


  <!-- vendor css -->
  <link href="{{env('APP_URL')}}/lib/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="{{env('APP_URL')}}/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="{{env('APP_URL')}}/lib/typicons.font/typicons.css" rel="stylesheet">
  <link href="{{env('APP_URL')}}/lib/prismjs/themes/prism-vs.css" rel="stylesheet">
  <link href="{{env('APP_URL')}}/lib/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="{{env('APP_URL')}}/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet">
  <link href="{{env('APP_URL')}}/lib/datatables.net-buttons/css/buttons.bootstrap.min.css" rel="stylesheet">
  <link href="{{env('APP_URL')}}/lib/select2/css/select2.min.css" rel="stylesheet">

  <!-- DashForge CSS -->
  <link rel="stylesheet" href="{{env('APP_URL')}}/assets/css/dashforge.css">
  <link rel="stylesheet" href="{{env('APP_URL')}}/assets/css/dashforge.dashboard.css">
  <!-- vue JS -->
  <link rel="stylesheet" href="{{env('APP_URL')}}/assets/primeicons-master/primeicons.css" />

  @yield('pagecss')
</head>

<body>

  


    <div class="content-body py-5 px-4" id="app">
      
      @yield('content')
    </div>


    
    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="{{env('APP_URL')}}/lib/jquery/jquery.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/feather-icons/feather.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>

    <!-- vendor script -->
    <script src="{{env('APP_URL')}}/lib/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/datatables.net-buttons/js/buttons.dataTables.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/datatables.net-buttons/js/buttons.bootstrap.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/datatables.net-buttons/js/jszip.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/datatables.net-buttons/js/pdfmake.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/datatables.net-buttons/js/vfs_fonts.js"></script>
    <script src="{{env('APP_URL')}}/lib/select2/js/select2.min.js"></script>
    <script src="{{env('APP_URL')}}/lib/jqueryui/jquery-ui.min.js"></script>

    <script src="{{env('APP_URL')}}/assets/js/dashforge.js"></script>
    <script src="{{env('APP_URL')}}/assets/js/dashforge.aside.js"></script>


    
</body>

</html>