<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title') | {{ config('app.name', 'Default') }}
    </title>
    @yield('meta')
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="{{ url('public/assets/demo/default/media/img/logo/favicon.ico') }}" /> 
    {!! Html::script('public/js/webfont.js') !!}
    <script>
        WebFont.load({
            google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
            active: function () {
                sessionStorage.fonts = true;
            }
        });
        var BASE_URL = "<?php echo URL::to('/').'/'; ?>";
    </script>
    <?php
        $version = "0.8";
    ?>

    <!-- Styles -->
    {!! Html::style('public/assets/vendors/base/vendors.bundle.css') !!}
    {!! Html::style('public/assets/demo/default/base/style.bundle.css') !!}
    {!! Html::style('public/css/min/app-bundle.min.css?'.$version) !!}
    <style type="text/css">
        @yield('customCss')
    </style>
</head>
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
    <div id="app" class="m-grid m-grid--hor m-grid--root m-page">
        @include('common.app.header')
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
            @include('common.app.left_menu')
            <router-view></router-view>
        </div>
        @include('common.app.footer')
    </div>

    <!-- Scripts -->
    
    {{-- {!! Html::script('public/js/min/app.min.js?'.$version) !!} --}}
    {!! Html::script('public/js/app.js') !!}
    {!! Html::script('public/assets/vendors/base/vendors.bundle.js') !!}
    {!! Html::script('public/assets/demo/default/base/scripts.bundle.js') !!}
    {!! Html::script('public/assets/demo/default/custom/components/base/toastr.js') !!}
    {!! Html::script('public/assets/demo/default/custom/components/base/blockui.js') !!}
    {!! Html::script('public/js/min/app-bundle.min.js') !!}
    <script type="text/javascript">
        @yield('customJs')
    </script>
</body>
</html>
