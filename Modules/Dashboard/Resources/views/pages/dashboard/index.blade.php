<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | {{ $dataUserAuth[0]->name }}</title>
    @include('dashboard::bases.dashboard.css')
    @include('dashboard::bases.dashboard.js')
</head>

<body>
    @include('dashboard::templates.dashboard.index')
</body>

</html>
