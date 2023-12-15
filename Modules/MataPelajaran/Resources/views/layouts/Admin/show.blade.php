<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | Mata Pelajaran {{ $dataMataPelajaran->name }}</title>
    @include('matapelajaran::bases.css')
    @include('matapelajaran::bases.js')

    <style>
        .link-with-margin {
            margin-right: 10px;
            margin-bottom: 10px;
        }

    </style>
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    @include('matapelajaran::components.sweetalert-success')
    @include('matapelajaran::components.sweetalert-error')

    <div class="main-wrapper">
        @include('dashboard::layouts.header')
        @include('dashboard::layouts.sidebar')

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Mata Pelajaran {{ $dataMataPelajaran->name }}</h4>
                    </div>

                    <div class="wordset">
                        <ul>
                            <a href="{{ route('download.data.mata.pelajaran', ['pdf', $dataMataPelajaran->uuid]) }}" class="link-with-margin">
                                <img src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img">
                            </a>

                            <a href="{{ route('download.data.mata.pelajaran', ['excel', $dataMataPelajaran->uuid]) }}" class="link-with-margin">
                                <img src="{{ asset('assets/dashboard/img/icons/excel.svg') }}" alt="img">
                            </a>

                            <a href="{{ route('edit.mata.pelajaran', $dataMataPelajaran->uuid) }}" class="link-with-margin">
                                <img src="{{ asset('assets/dashboard/img/icons/edit.svg') }}" alt="img">
                            </a>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="productdetails">
                                    <ul class="product-bar">
                                        <li>
                                            <h4>Nama</h4>
                                            <h6>{{ $dataMataPelajaran->name }}</h6>
                                        </li>

                                        <li>
                                            <h4>Jam Awal</h4>
                                            <h6>{{ $dataMataPelajaran->jam_awal }}</h6>
                                        </li>

                                        <li>
                                            <h4>Jam Akhir</h4>
                                            <h6>{{ $dataMataPelajaran->jam_akhir }}</h6>
                                        </li>

                                        <li>
                                            <h4>Kelas</h4>
                                            <h6>{{ $dataMataPelajaran->kelas }}</h6>
                                        </li>

                                        <li>
                                            <h4>Guru</h4>
                                            <h6>{{ $dataMataPelajaran->guru ? $dataMataPelajaran->guru->name : '' }}</h6>
                                        </li>

                                        <li>
                                            <h4>Materi Pdf</h4>
                                            @if ($dataMataPelajaran->materi_pdf)
                                            <h6>
                                                <a href="{{ route('download.pdf.mata.pelajaran', $dataMataPelajaran->uuid) }}">Download materi pdf</a>    
                                            </h6>
                                            @endif
                                        </li>

                                        <li>
                                            <h4>Materi Ppt</h4>
                                            @if ($dataMataPelajaran->materi_ppt)
                                            <h6>
                                                <a href="{{ route('download.ppt.mata.pelajaran', $dataMataPelajaran->uuid) }}">Download materi ppt</a>    
                                            </h6>
                                            @endif
                                        </li>

                                        <li>
                                            <h4>Materi Video</h4>
                                            @if ($dataMataPelajaran->video)
                                            <h6>
                                                <a href="{{ $dataMataPelajaran->video }}" target="_blank">Link Youtube</a>    
                                            </h6>                                                
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="slider-product-details">
                                    <div class="owl-carousel owl-theme product-slide">
                                        <div class="slider-product">
                                            <img src="{{ $dataMataPelajaran->foto ? asset($dataMataPelajaran->foto) : asset('assets/dashboard/img/mapel.png') }}" alt="img">                                            
                                            <h4>Foto</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
