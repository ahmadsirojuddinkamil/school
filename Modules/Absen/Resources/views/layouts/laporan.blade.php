<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | laporan absen</title>
    @include('absen::bases.css')
    @include('absen::bases.js')
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    @include('absen::components.sweetalert-success')
    @include('absen::components.sweetalert-error')

    <div class="main-wrapper">
        @include('dashboard::layouts.header')
        @include('dashboard::layouts.sidebar')

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Laporan Absen</h4>

                        @if ($dataUserAuth[1] == 'siswa')
                        <h6>{{ $dataUserAuth[0]->name }} | Kelas : {{ $dataUserAuth[0]->siswa->kelas }} | Total {{ $listKehadiran['totalAbsen'] }} Laporan *tidak termasuk tidak hadir!</h6>
                        @elseif($dataUserAuth[1] == 'guru')
                        <h6>{{ $dataUserAuth[0]->name }} | Mata Pelajaran : {{ $dataUserAuth[0]->guru->mata_pelajaran }} | Total {{ $listKehadiran['totalAbsen'] }} Laporan *tidak termasuk tidak hadir!</h6>
                        @endif
                    </div>
                </div>

                <div class="card w-75">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-input">
                                    <a class="btn btn-searchset">
                                        <img src="{{ asset('assets/dashboard/img/icons/search-white.svg') }}" alt="img">
                                    </a>
                                </div>
                            </div>

                            <div class="wordset">
                                <ul>
                                    <li>
                                        <a href="{{ route('laporan.absen.pdf') }}">
                                            <img src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table  datanew">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal & Jam</th>
                                        <th>Hari</th>
                                        <th>keterangan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php
                                    $namaHari = [
                                    1 => 'Senin',
                                    2 => 'Selasa',
                                    3 => 'Rabu',
                                    4 => 'Kamis',
                                    5 => 'Jumat',
                                    6 => 'Sabtu',
                                    7 => 'Minggu',
                                    ];
                                    @endphp

                                    @foreach ($listAbsen as $absen)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $absen->created_at }}</td>
                                        <td>{{ $namaHari[$absen->created_at->format('N')] }}</td>

                                        @if ($absen->keterangan == 'hadir')
                                        <td>
                                            <span class="badges bg-lightgreen">{{ $absen->keterangan }}</span>
                                        </td>
                                        @elseif ($absen->keterangan == 'sakit' || $absen->keterangan == 'acara' || $absen->keterangan == 'musibah')
                                        <td>
                                            <span class="badges bg-lightyellow">{{ $absen->keterangan }}</span>
                                        </td>
                                        @else
                                        <td>
                                            <span class="badges bg-lightred">tidak hadir</span>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</body>

</html>
