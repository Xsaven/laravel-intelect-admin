<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ Admin::title() }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="{{ admin_asset("/vendor/lia/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ admin_asset("/vendor/lia/font-awesome/css/font-awesome.min.css") }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ admin_asset("/vendor/lia/AdminLTE/dist/css/skins/" . config('lia.skin') .".min.css") }}">

    {!! Admin::css() !!}
    <link rel="stylesheet" href="{{ admin_asset("/vendor/lia/laravel-admin/laravel-admin.css") }}">
    @if(config('lia.layout')[0]=='fixed')
        <link rel="stylesheet" href="{{ admin_asset("/vendor/lia/nprogress/fixed-nprogress.css") }}">
    @else
        <link rel="stylesheet" href="{{ admin_asset("/vendor/lia/nprogress/nprogress.css") }}">
    @endif
    <link rel="stylesheet" href="{{ admin_asset("/vendor/lia/sweetalert/dist/sweetalert.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("/vendor/lia/nestable/nestable.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("/vendor/lia/toastr/build/toastr.min.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("/vendor/lia/bootstrap3-editable/css/bootstrap-editable.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("/vendor/lia/google-fonts/fonts.css") }}">
    <link rel="stylesheet" href="{{ admin_asset("/vendor/lia/AdminLTE/dist/css/AdminLTE.min.css") }}">

    <!-- REQUIRED JS SCRIPTS -->
    <script src="{{ admin_asset ("/vendor/lia/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
    <script src="{{ admin_asset ("/vendor/lia/AdminLTE/bootstrap/js/bootstrap.min.js") }}"></script>
    <script src="{{ admin_asset ("/vendor/lia/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js") }}"></script>
    <script src="{{ admin_asset ("/vendor/lia/AdminLTE/dist/js/app.min.js") }}"></script>
    <script src="{{ admin_asset ("/vendor/lia/jquery-pjax/jquery.pjax.js") }}"></script>
    <script src="{{ admin_asset ("/vendor/lia/nprogress/nprogress.js") }}"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="hold-transition {{config('lia.skin')}} {{join(' ', config('lia.layout'))}}">
<div class="wrapper">

    @include('lia::partials.header')

    @include('lia::partials.sidebar')

    <div class="content-wrapper" id="pjax-container">
        @yield('content')
        {!! Admin::script() !!}
    </div>

    @include('lia::partials.footer')

</div>

<!-- ./wrapper -->

<script>
    function LA() {}
    LA.token = "{{ csrf_token() }}";
</script>

<!-- REQUIRED JS SCRIPTS -->
<script src="{{ admin_asset ("/vendor/lia/nestable/jquery.nestable.js") }}"></script>
<script src="{{ admin_asset ("/vendor/lia/toastr/build/toastr.min.js") }}"></script>
<script src="{{ admin_asset ("/vendor/lia/bootstrap3-editable/js/bootstrap-editable.min.js") }}"></script>
<script src="{{ admin_asset ("/vendor/lia/sweetalert/dist/sweetalert.min.js") }}"></script>
{!! Admin::js() !!}
<script src="{{ admin_asset ("/vendor/lia/laravel-admin/laravel-admin.js") }}"></script>

</body>
</html>
