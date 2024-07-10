<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        Admin Login | {{ config('app.name', 'Dashboard') }}
    </title>
    @yield('meta')
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ url( 'public/assets/demo/default/media/img/logo/favicon.ico') }}" /> 
    {!! Html::script('public/js/webfont.js') !!}
    <script>
        WebFont.load({
            google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- Styles -->

    {!! Html::style('public/assets/vendors/base/vendors.bundle.css') !!}
    {!! Html::style('public/assets/demo/default/base/style.bundle.css') !!}
    {!! Html::style('public/css/min/app-bundle.min.css') !!}
</head>
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
    <div id="app" class="m-grid m-grid--hor m-grid--root m-page">
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-2" id="m_login" style="background-image: url({{ asset('public/assets/app/media/img/bg/bg-3.jpg') }});">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    {!! Html::script('public/assets/vendors/base/vendors.bundle.js') !!}
    {!! Html::script('public/assets/demo/default/base/scripts.bundle.js') !!}
    {!! Html::script('public/js/min/app-bundle.min.js') !!}
</body>
</html>