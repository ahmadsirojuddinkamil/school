<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | data absen siswa</title>
    @include('absen::bases.css')
    @include('absen::bases.js')
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
                <div class="page-header">
                    <div class="page-title">
                        <h4>Daftar Absen Siswa</h4>
                    </div>
                </div>

                @include('absen::components.sweetalert-success')
                @include('absen::components.sweetalert-error')

                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <a href="{{ route('data.absen.class', 12) }}">
                            <div class="dash-count das1">
                                <div class="dash-counts">
                                    <h4>Kelas 12</h4>
                                    @if($listSiswaInClass)
                                    <h5>Jumlah Absen : {{ $listSiswaInClass['12'] }}</h5>
                                    @endif
                                </div>

                                <div class="dash-imgs">
                                    <i data-feather="book-open"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <a href="{{ route('data.absen.class', 11) }}">
                            <div class="dash-count das1">
                                <div class="dash-counts">
                                    <h4>Kelas 11</h4>
                                    @if($listSiswaInClass)
                                    <h5>Jumlah Absen : {{ $listSiswaInClass['11'] }}</h5>
                                    @endif
                                </div>

                                <div class="dash-imgs">
                                    <i data-feather="book-open"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <a href="{{ route('data.absen.class', 10) }}">
                            <div class="dash-count das1">
                                <div class="dash-counts">
                                    <h4>Kelas 10</h4>
                                    @if($listSiswaInClass)
                                    <h5>Jumlah Absen : {{ $listSiswaInClass['10'] }}</h5>
                                    @endif
                                </div>

                                <div class="dash-imgs">
                                    <i data-feather="book-open"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
