<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" />
    <title></title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('admin/client_admin/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/client_admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/client_admin/css/custom.css') }}">

    {{-- @include('include.backend_css') --}}
    <style>
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body>
    <div id="app">
        <router-view></router-view>
    </div>
    <script>window.Laravel = {csrfToken: '{{ csrf_token() }}'}</script>
    <script src="{{ asset('js/client.js') }}" type="text/javascript"></script>
</body>

</html>
