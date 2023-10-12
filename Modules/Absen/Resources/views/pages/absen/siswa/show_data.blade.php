<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | data absen {{ $dataUserAuth[0]->name }}</title>
    @include('absen::bases.absen.css')
    @include('absen::bases.absen.js')
</head>

<body>
    @include('absen::templates.absen.siswa.show_data')
</body>

</html>
