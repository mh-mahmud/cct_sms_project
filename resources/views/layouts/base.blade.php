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
    <link rel="shortcut icon" href="{{ url( 'public/assets/demo/default/media/img/logo/favicon.ico') }}" /> 
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

    <!-- Styles -->
    {!! Html::style('public/assets/vendors/base/vendors.bundle.css') !!}
    {!! Html::style('public/assets/demo/default/base/style.bundle.css') !!}
    <!-- plugin css -->
    @if(isset($js_plugin['css']) && !empty($js_plugin['css']))
        @foreach($js_plugin['css'] as $css)
            <link type="text/css" rel="stylesheet" href="{{url($css)}}">
        @endforeach
    @endif
    {!! Html::style('public/css/min/app-bundle.min.css') !!}
    <style type="text/css">
        @yield('customCss')
    </style>
</head>
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
    <div id="app" class="m-grid m-grid--hor m-grid--root m-page">
        @include('common.app.header')
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
            @include('common.app.left_menu')
            @yield('content')
        </div>
        @include('common.app.footer')
    </div>

    <!-- Scripts -->
    {!! Html::script('public/assets/vendors/base/vendors.bundle.js') !!}
    {!! Html::script('public/assets/demo/default/base/scripts.bundle.js') !!}
    {!! Html::script('public/assets/demo/default/custom/components/base/toastr.js') !!}
    {!! Html::script('public/assets/demo/default/custom/components/base/blockui.js') !!}
    <!-- plugin js -->
    @if(isset($js_plugin['js']) && !empty($js_plugin['js']))
        @foreach($js_plugin['js'] as $js)
            <script src="{{url($js)}}"></script>
        @endforeach
    @endif
    {!! Html::script('public/js/min/app-bundle.min.js') !!}
    <script type="text/javascript">
        @yield('customJs')
    </script>
</body>
</html>
