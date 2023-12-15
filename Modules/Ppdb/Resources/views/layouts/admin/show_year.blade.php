<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | ppdb {{ $saveYearFromCall }}</title>
    @include('ppdb::bases.css')
    @include('ppdb::bases.js')

    <style>
        .icon-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 2px;
        }

        .icon-list li {
            display: inline;
        }

    </style>
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    @include('ppdb::components.sweetalert-success')
    @include('ppdb::components.sweetalert-error')

    <div class="main-wrapper">
        @include('dashboard::layouts.header')
        @include('dashboard::layouts.sidebar')
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>PPDB tahun {{ $dataPpdb[0]->tahun_daftar }} : {{ $totalPpdb }} peserta</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-input">
                                    <a class="btn btn-searchset"><img src="{{ asset('assets/dashboard/img/icons/search-white.svg') }}" alt="img"></a>
                                </div>
                            </div>

                            <div class="wordset">
                                <ul class="icon-list">
                                    <li>
                                        <a href="{{ route('ppdb.download.pdf.zip', ['save_year_from_event' => $dataPpdb[0]->tahun_daftar]) }}" title="pdf">
                                            <img src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img">
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('ppdb.download.excel.zip', ['save_year_from_event' => $dataPpdb[0]->tahun_daftar]) }}" title="excel">
                                            <img src="{{ asset('assets/dashboard/img/icons/excel.svg') }}" alt="img">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        @include('ppdb::components.data-ppdb-table')
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('downloadPdfLink').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('downloadPdfForm').submit();
        });

        document.getElementById('downloadExcelLink').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('downloadExcelForm').submit();
        });

    </script>

</body>

</html>
