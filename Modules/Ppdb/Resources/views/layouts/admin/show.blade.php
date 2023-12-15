<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | ppdb {{ $dataPpdb->name }}</title>
    @include('ppdb::bases.css')
    @include('ppdb::bases.js')
</head>

<body>
    @include('ppdb::components.sweetalert-success')
    @include('ppdb::components.sweetalert-error')

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
                        <h4>Biodata Peserta Didik Baru</h4>
                    </div>

                    @include('ppdb::components.accept-ppdb')
                </div>

                <div class="row">
                    @include('ppdb::components.data-user-ppdb')
                    @include('ppdb::components.bukti-pembayaran')
                </div>

            </div>
        </div>
    </div>

</body>

</html>
