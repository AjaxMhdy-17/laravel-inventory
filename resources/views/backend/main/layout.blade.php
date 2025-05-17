<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    @include('backend.main.cssLibs')
    @stack('css')

    <style>
        .error__msg {
            color: red;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center" style="width : 100%">
            <img class="animation__shake img-fluid" src="{{ asset('backend/assets/dist/img/AdminLTELogo.png') }}"
                alt="AdminLTELogo" height="60" width="60">
        </div> --}}

        <!-- Navbar -->
        @include('backend.main.header')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('backend.main.sidebar')


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->


        @include('backend.main.footer')

    </div>
    <!-- ./wrapper -->



    @include('backend.main.jsLibs')
    @stack('js')


</body>

</html>
