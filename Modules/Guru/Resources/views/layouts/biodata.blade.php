<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | Data Guru {{ $dataUserAuth[0]->name }}</title>
    @include('guru::bases.css')
    @include('guru::bases.js')

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

    @include('guru::components.sweetalert-success')
    @include('guru::components.sweetalert-error')

    <div class="main-wrapper">
        @include('dashboard::layouts.header')
        @include('dashboard::layouts.sidebar')

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Biodata Guru</h4>
                    </div>

                    <div class="wordset">
                        <ul>
                            <a href="{{ route('data.guru.download.pdf', ['save_uuid_from_event' => $dataGuru->uuid]) }}" class="link-with-margin">
                                <img src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img">
                            </a>

                            @if(in_array($dataUserAuth[1], ['super_admin', 'admin']))
                            <a href="{{ route('data.guru.download.excel', ['save_uuid_from_event' => $dataGuru->uuid]) }}" class="link-with-margin">
                                <img src="{{ asset('assets/dashboard/img/icons/excel.svg') }}" alt="img">
                            </a>
                            @endif

                            <a href="{{ route('data.guru.edit', $dataGuru->uuid) }}" class="link-with-margin">
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
                                            <h6>{{ $dataGuru->name }}</h6>
                                        </li>

                                        <li>
                                            <h4>nuptk</h4>
                                            <h6>{{ $dataGuru->nuptk }}</h6>
                                        </li>

                                        <li>
                                            <h4>nip</h4>
                                            <h6>{{ $dataGuru->nip }}</h6>
                                        </li>

                                        <li>
                                            <h4>tempat lahir</h4>
                                            <h6>{{ $dataGuru->tempat_lahir }}</h6>
                                        </li>

                                        <li>
                                            <h4>tanggal lahir</h4>
                                            <h6>{{ $dataGuru->tanggal_lahir }}</h6>
                                        </li>

                                        <li>
                                            <h4>agama</h4>
                                            <h6>{{ $dataGuru->agama }}</h6>
                                        </li>

                                        <li>
                                            <h4>Jenis Kelamin</h4>
                                            <h6>{{ $dataGuru->jenis_kelamin }}</h6>
                                        </li>

                                        <li>
                                            <h4>Status Perkawinan</h4>
                                            <h6>{{ $dataGuru->status_perkawinan }}</h6>
                                        </li>

                                        <li>
                                            <h4>Jam Mengajar Awal</h4>
                                            <h6>{{ $dataGuru->jam_mengajar_awal }}</h6>
                                        </li>

                                        <li>
                                            <h4>Jam Mengajar Akhir</h4>
                                            <h6>{{ $dataGuru->jam_mengajar_akhir }}</h6>
                                        </li>

                                        <li>
                                            <h4>Pendidikan Terakhir</h4>
                                            <h6>{{ $dataGuru->pendidikan_terakhir }}</h6>
                                        </li>

                                        <li>
                                            <h4>Nama Tempat Pendidikan</h4>
                                            <h6>{{ $dataGuru->nama_tempat_pendidikan }}</h6>
                                        </li>

                                        <li>
                                            <h4>Ipk</h4>
                                            <h6>{{ $dataGuru->ipk }}</h6>
                                        </li>

                                        <li>
                                            <h4>Tahun_lulus</h4>
                                            <h6>{{ $dataGuru->tahun_lulus }}</h6>
                                        </li>

                                        <li>
                                            <h4>Alamat Rumah</h4>
                                            <h6>{{ $dataGuru->alamat_rumah }}</h6>
                                        </li>

                                        <li>
                                            <h4>Provinsi</h4>
                                            <h6>{{ $dataGuru->provinsi }}</h6>
                                        </li>

                                        <li>
                                            <h4>Kecamatan</h4>
                                            <h6>{{ $dataGuru->kecamatan }}</h6>
                                        </li>


                                        <li>
                                            <h4>Kelurahan</h4>
                                            <h6>{{ $dataGuru->kelurahan }}</h6>
                                        </li>


                                        <li>
                                            <h4>Kode Pos</h4>
                                            <h6>{{ $dataGuru->kode_pos }}</h6>
                                        </li>


                                        <li>
                                            <h4>Email</h4>
                                            <h6>{{ $dataGuru->email }}</h6>
                                        </li>

                                        <li>
                                            <h4>No Telpon</h4>
                                            <h6>{{ $dataGuru->no_telpon }}</h6>
                                        </li>

                                        <li>
                                            <h4>Tahun Daftar</h4>
                                            <h6>{{ $dataGuru->tahun_daftar }}</h6>
                                        </li>

                                        <li>
                                            <h4>Tahun Keluar</h4>
                                            @if($dataGuru->tahun_keluar)
                                            <h6>{{ $dataGuru->tahun_keluar }}</h6>
                                            @else
                                            <h6>aktif</h6>
                                            @endif
                                        </li>

                                        <li>
                                            <h4>Nama Bank</h4>
                                            <h6>{{ $dataGuru->nama_bank }}</h6>
                                        </li>

                                        <li>
                                            <h4>Nama Buku Rekening</h4>
                                            <h6>{{ $dataGuru->nama_buku_rekening }}</h6>
                                        </li>

                                        <li>
                                            <h4>No Rekening</h4>
                                            <h6>{{ $dataGuru->no_rekening }}</h6>
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
                                            <img src="{{ $dataGuru->foto ? asset($dataGuru->foto) : 'https://static.vecteezy.com/system/resources/previews/008/442/086/non_2x/illustration-of-human-icon-user-symbol-icon-modern-design-on-blank-background-free-vector.jpg' }}" alt="img">
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
