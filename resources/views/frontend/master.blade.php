
<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8" />
    <meta name="format-detection" content="telephone=no" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">


    <title> Mohasagor </title>
    <link href="/public/images/favicon.png" rel="icon" />
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('frontend.partials.css')

</head>

<body>

    <div id="app">

        <router-view :key="$route.fullPath"></router-view>
        <vue-progress-bar></vue-progress-bar>

    </div>


    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>

</body>

</html>
