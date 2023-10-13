<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | ppdb show</title>
    @include('ppdb::bases.ppdb.css')
    @include('ppdb::bases.ppdb.js')
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
                        <h4>Peserta PPDB Details</h4>
                    </div>

                    @include('ppdb::components.ppdb.accept-ppdb')
                </div>

                <div class="row">
                    @include('ppdb::components.ppdb.data-user-ppdb')
                    @include('ppdb::components.ppdb.bukti-pembayaran')
                </div>

            </div>
        </div>
    </div>

</body>

</html>
