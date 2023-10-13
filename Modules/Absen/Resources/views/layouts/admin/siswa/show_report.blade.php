<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | data absen {{ $dataUserAuth[0]->name }}</title>
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
                        <h4>Laporan Absen</h4>

                        @php
                        $kelas = [
                        1 => '10',
                        2 => '11',
                        3 => '12',
                        ];
                        @endphp

                        @if(in_array($getDataAbsen[0]->status, $kelas))
                        <h6>{{ $getDataAbsen[0]->name }} | Kelas {{ $getDataAbsen[0]->status }}</h6>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set mr-2">
                                <div class="search-input">
                                    <a class="btn btn-searchset">
                                        <img src="{{ asset('assets/dashboard/img/icons/search-white.svg') }}" alt="img">
                                    </a>
                                </div>
                            </div>

                            <div class="wordset">
                                <ul>
                                    <li>
                                        <form action="{{ route('laporan.absen.pdf') }}" method="POST">
                                            @csrf
                                            <style>
                                                .no-background-button {
                                                    background: none;
                                                    border: none;
                                                    padding: 0;
                                                }

                                            </style>
                                            <input type="hidden" name="nisn" value="{{ $getDataAbsen[0]->nisn }}">

                                            <button type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="pdf" class="no-background-button">
                                                <img src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img">
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mb-4">
                            <span>hadir : {{ $listKehadiran['totalHadir'] }}</span> |
                            <span>sakit : {{ $listKehadiran['totalSakit'] }}</span> |
                            <span>acara : {{ $listKehadiran['totalAcara'] }}</span> |
                            <span>musibah : {{ $listKehadiran['totalMusibah'] }}</span> |
                            <span>tidak hadir : {{ $listKehadiran['totalTidakHadir'] }}</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table  datanew">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal & Jam</th>
                                        <th>Hari</th>
                                        <th>Kehadiran</th>
                                        <th>Action</th>
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

                                    @foreach ($getDataAbsen as $absen)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $absen->updated_at }}</td>
                                        <td>{{ $namaHari[$absen->updated_at->format('N')] }}</td>

                                        @if ($absen->kehadiran == 'hadir')
                                        <td>
                                            <span class="badges bg-lightgreen">{{ $absen->kehadiran }}</span>
                                        </td>
                                        @elseif ($absen->kehadiran == 'sakit' || $absen->kehadiran == 'acara' || $absen->kehadiran == 'musibah')
                                        <td>
                                            <span class="badges bg-lightyellow">{{ $absen->kehadiran }}</span>
                                        </td>
                                        @else
                                        <td>
                                            <span class="badges bg-lightred">tidak hadir</span>
                                        </td>
                                        @endif

                                        <td class="actions">
                                            <a class="action-link" href="{{ route('data.tanggal.absen.edit', ['save_uuid_from_event' => $absen->uuid ,'save_date_from_event' => $absen->created_at]) }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/edit.svg') }}" alt="img">
                                            </a>

                                            <button class="action-button" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $absen->uuid }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/delete.svg') }}" alt="img">
                                            </button>

                                            <div class="modal fade" id="modalDelete{{ $absen->uuid }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            Data absen <span style="font-weight: bold">{{ $absen->created_at }}</span> akan terhapus!
                                                        </div>

                                                        <div class="modal-footer d-flex justify-content-end">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                                                            <form action="{{ route('data.tanggal.absen.delete') }}" method="POST" class="action-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="tanggal" value="{{ $absen->created_at->format('Y-m-d H:i:s') }}">
                                                                <input type="hidden" name="nisn" value="{{ $absen->nisn }}">
                                                                <button type="submit" class="btn btn-submit me-2">Submit</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
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
