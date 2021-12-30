<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Prism Homepage') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->

    <link href="{{ asset('css/shineCompliance/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/shineCompliance/utility.css') }}" rel="stylesheet">
    <link href="{{ asset('css/shineCompliance/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">

    <script src='https://www.google.com/recaptcha/api.js'></script>
    <!-- for Stock Chart -->
    {{--    <script src="https://code.highcharts.com/indicators/indicators-all.js"></script>--}}
    {{--    <script src="https://code.highcharts.com/indicators/indicators.js"></script>--}}
    {{--    <script src="https://code.highcharts.com/indicators/rsi.js"></script>--}}
    {{--    <script src="https://code.highcharts.com/indicators/ema.js"></script>--}}
    {{--    <script src="https://code.highcharts.com/indicators/macd.js"></script>--}}
    {{--    <script src="https://code.highcharts.com/modules/drag-panes.js"></script>--}}
    {{--    <script src="https://code.highcharts.com/modules/annotations-advanced.js"></script>--}}
    {{--    <script src="https://code.highcharts.com/modules/price-indicator.js"></script>--}}
    {{--    <script src="https://code.highcharts.com/modules/full-screen.js"></script>--}}
    {{--    <script src="https://code.highcharts.com/modules/stock-tools.js"></script>--}}
    {{--    <link rel="stylesheet" type="text/css" href="https://code.highcharts.com/css/stocktools/gui.css">--}}
    {{--    <link rel="stylesheet" type="text/css" href="https://code.highcharts.com/css/annotations/popup.css">--}}

    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css/shineCompliance/select2-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/shineCompliance/bstree.css') }}" rel="stylesheet">
    <link href="{{ asset('css/shineCompliance/simple-sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/shineCompliance/easy-autocomplete.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/shineCompliance/easy-autocomplete.themes.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/shineCompliance/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/shineCompliance/jquery-ui.theme.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <link href="{{ asset('css/shineCompliance/toastr.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    @stack('css')

</head>
<body>
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    Loading...
</div>
{{-- main content --}}
@yield('content')
{{-- end of main content --}}
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="{{ asset('js/shineCompliance/jquery-ui.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
<script src="{{ asset('js/shineCompliance/jquery.bstree.js') }}"></script>
<script src="{{ asset('js/shineCompliance/jquery.easy-autocomplete.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/maphilight/1.4.0/jquery.maphilight.min.js"></script>

<!-- Scripts -->

<script src="{{ asset('js/shineCompliance/highstocks.js') }}"></script>
<script src="{{ asset('js/shineCompliance/exporting.js') }}"></script>
<script src="{{ asset('js/shineCompliance/export-data.js') }}"></script>
<script src="{{ asset('js/shineCompliance/accessibility.js') }}"></script>

<script src="{{ asset('js/shineCompliance/myscript.js') }}"></script>
{{--    <script>--}}
{{--        var HighchartsStock = Highcharts;--}}
{{--        Highcharts = null;--}}
{{--    </script>--}}
{{--    <script src="{{ asset('js/shineCompliance/highcharts.js') }}"></script>--}}
<script>
    // console.log(HighchartsStock);
</script>
@stack('javascript')
</body>
</html>
