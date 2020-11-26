<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{ url('assets/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ url('css/adminlte.min.css') }}">
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">

            @include('layouts.partials.admin.navbar')

            @include('layouts.partials.admin.left')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                @include('layouts.partials.admin.content_header')

                <!-- Main content -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            @include('layouts.partials.flash')
                            @yield('content')
                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            @include('layouts.partials.admin.footer')

        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED SCRIPTS -->
        <!-- jQuery -->
        <script src="{{ url('assets/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ url('js/adminlte.min.js') }}"></script>
    </body>
</html>