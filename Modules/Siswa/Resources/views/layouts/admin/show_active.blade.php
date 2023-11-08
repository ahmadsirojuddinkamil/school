<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | biodata {{ $dataSiswa->name }}</title>

    @include('siswa::bases.css')
    @include('siswa::bases.js')

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

    <div class="main-wrapper">
        @include('dashboard::layouts.header')
        @include('dashboard::layouts.sidebar')

        @include('guru::components.sweetalert-success')
        @include('guru::components.sweetalert-error')

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Biodata Siswa</h4>
                    </div>

                    <div class="wordset">
                        <ul>
                            <a href="{{ route('siswa.active.download.pdf', $dataSiswa->uuid) }}" class="link-with-margin">
                                <img src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img">
                            </a>

                            @if(in_array($dataUserAuth[1], ['super_admin', 'admin']))
                            <a href="{{ route('siswa.active.download.excel', $dataSiswa->uuid) }}" class="link-with-margin">
                                <img src="{{ asset('assets/dashboard/img/icons/excel.svg') }}" alt="img">
                            </a>
                            @endif

                            <a href="{{ route('siswa.edit', $dataSiswa->uuid) }}" class="link-with-margin">
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
                                            <h6>{{ $dataSiswa->name }}</h6>
                                        </li>

                                        <li>
                                            <h4>NISN</h4>
                                            <h6>{{ $dataSiswa->nisn }}</h6>
                                        </li>

                                        <li>
                                            <h4>Kelas</h4>
                                            <h6>{{ $dataSiswa->kelas }}</h6>
                                        </li>

                                        <li>
                                            <h4>Tempat Lahir</h4>
                                            <h6>{{ $dataSiswa->tempat_lahir }}</h6>
                                        </li>

                                        <li>
                                            <h4>Tanggal Lahir</h4>
                                            <h6>{{ $dataSiswa->tanggal_lahir }}</h6>
                                        </li>

                                        <li>
                                            <h4>Agama</h4>
                                            <h6>{{ $dataSiswa->agama }}</h6>
                                        </li>

                                        <li>
                                            <h4>Jenis Kelamin</h4>
                                            <h6>{{ $dataSiswa->jenis_kelamin }}</h6>
                                        </li>

                                        <li>
                                            <h4>Asal Sekolah Smp / Mts</h4>
                                            <h6>{{ $dataSiswa->asal_sekolah }}</h6>
                                        </li>

                                        <li>
                                            <h4>Nem</h4>
                                            <h6>{{ $dataSiswa->nem }}</h6>
                                        </li>

                                        <li>
                                            <h4>Tahun Lulus</h4>
                                            <h6>{{ $dataSiswa->tahun_lulus }}</h6>
                                        </li>

                                        <li>
                                            <h4>Alamat</h4>
                                            <h6>{{ $dataSiswa->alamat_rumah }}</h6>
                                        </li>

                                        <li>
                                            <h4>Provinsi</h4>
                                            <h6>{{ $dataSiswa->provinsi }}</h6>
                                        </li>

                                        <li>
                                            <h4>Kecamatan</h4>
                                            <h6>{{ $dataSiswa->kecamatan }}</h6>
                                        </li>

                                        <li>
                                            <h4>Kelurahan</h4>
                                            <h6>{{ $dataSiswa->kelurahan }}</h6>
                                        </li>

                                        <li>
                                            <h4>Kode Pos</h4>
                                            <h6>{{ $dataSiswa->kode_pos }}</h6>
                                        </li>

                                        <li>
                                            <h4>Email</h4>
                                            <h6>{{ $dataSiswa->email }}</h6>
                                        </li>

                                        <li>
                                            <h4>No Telpon</h4>
                                            <h6>{{ $dataSiswa->no_telpon }}</h6>
                                        </li>

                                        <li>
                                            <h4>Tahun Daftar</h4>
                                            <h6>{{ $dataSiswa->tahun_daftar }}</h6>
                                        </li>

                                        <li>
                                            <h4>Nama Bank</h4>
                                            <h6>{{ $dataSiswa->nama_bank }}</h6>
                                        </li>

                                        <li>
                                            <h4>Nama Pemilik Buku Rekening</h4>
                                            <h6>{{ $dataSiswa->nama_buku_rekening }}</h6>
                                        </li>

                                        <li>
                                            <h4>No Rekening</h4>
                                            <h6>{{ $dataSiswa->no_rekening }}</h6>
                                        </li>

                                        <li>
                                            <h4>Nama Ayah</h4>
                                            <h6>{{ $dataSiswa->nama_ayah }}</h6>
                                        </li>

                                        <li>
                                            <h4>Nama Ibu</h4>
                                            <h6>{{ $dataSiswa->nama_ibu }}</h6>
                                        </li>

                                        <li>
                                            <h4>Nama Wali</h4>
                                            <h6>{{ $dataSiswa->nama_wali }}</h6>
                                        </li>

                                        <li>
                                            <h4>Telpon Orang Tua</h4>
                                            <h6>{{ $dataSiswa->telpon_orang_tua }}</h6>
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
                                            <img src="{{ $dataSiswa->foto ? asset($dataSiswa->foto) : 'https://static.vecteezy.com/system/resources/previews/008/442/086/non_2x/illustration-of-human-icon-user-symbol-icon-modern-design-on-blank-background-free-vector.jpg' }}" alt="img">
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
</body>

</html>
