<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | {{ $dataUserAuth[0]->name }}</title>
    @include('dashboard::bases.css')
    @include('dashboard::bases.js')
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">
        @include('dashboard::layouts.header')
        @include('dashboard::layouts.sidebar')

        <div class="page-wrapper">
            <div class="content">
                @include('dashboard::components.sweetalert-success')
                @include('dashboard::components.sweetalert-error')

                <h1>Hello, {{ $dataUserAuth[0]->name }}</h1>
            </div>
        </div>
    </div>

</body>

</html>
