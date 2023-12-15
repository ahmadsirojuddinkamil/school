<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | data absen {{ $dataUserAuth[0]->name }}</title>
    @include('absen::bases.css')
    @include('absen::bases.js')

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

                        <h6>{{ $dataGuru->name }} | Guru | Total {{ $listKehadiran['totalAbsen'] }} Laporan *tidak termasuk tidak hadir!</h6>
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
                                    <a href="{{ route('data.absen.guru.download.pdf', ['save_uuid_from_event' => $dataGuru->uuid]) }}" class="link-with-margin">
                                        <img src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img">
                                    </a>

                                    <a href="{{ route('data.absen.guru.download.excel', ['save_uuid_from_event' => $dataGuru->uuid]) }}" class="link-with-margin">
                                        <img src="{{ asset('assets/dashboard/img/icons/excel.svg') }}" alt="img">
                                    </a>
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

                                    @foreach ($listAbsen as $absen)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $absen->updated_at }}</td>
                                        <td>{{ $namaHari[$absen->updated_at->format('N')] }}</td>

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

                                        <td class="actions">
                                            <a class="action-link" href="{{ route('data.absen.guru.date.edit', ['save_uuid_from_event' => $absen->uuid, 'save_date_from_event' => $absen->updated_at]) }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/edit.svg') }}" alt="img">
                                            </a>

                                            @if($dataUserAuth[1] == 'super_admin')
                                            <button class="action-button" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $absen->id }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/delete.svg') }}" alt="img">
                                            </button>

                                            <div class="modal fade" id="modalDelete{{ $absen->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

                                                            <form action="{{ route('data.absen.guru.date.delete') }}" method="POST" class="action-form">
                                                                @csrf
                                                                @method('DELETE')

                                                                <input type="hidden" name="uuid" value="{{ $absen->uuid }}" required>
                                                                <button type="submit" class="btn btn-submit me-2">Submit</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
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
