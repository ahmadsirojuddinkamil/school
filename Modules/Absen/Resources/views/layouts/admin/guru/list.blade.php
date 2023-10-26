<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | data absen guru</title>
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

    <div class="main-wrapper">
        @include('dashboard::layouts.header')
        @include('dashboard::layouts.sidebar')

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Data Absen Guru | Total : Laporan</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-input">
                                    <a class="btn btn-searchset">
                                        <img src="{{ asset('assets/dashboard/img/icons/search-white.svg') }}" alt="img">
                                    </a>
                                </div>
                            </div>

                            @include('absen::components.sweetalert-success')
                            @include('absen::components.sweetalert-error')

                            <div class="wordset">
                                <ul>
                                    <a href="{{ route('data.absen.guru.download.pdf.zip') }}" class="link-with-margin">
                                        <img src="{{ asset('assets/dashboard/img/icons/pdf.svg') }}" alt="img">
                                    </a>

                                    <a href="{{ route('data.absen.guru.download.excel.zip') }}" class="link-with-margin">
                                        <img src="{{ asset('assets/dashboard/img/icons/excel.svg') }}" alt="img">
                                    </a>
                                </ul>
                            </div>
                        </div>

                        <div class="table-responsiv">
                            <table class="table  datanew">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <th>Nama</th>
                                        {{-- <th>NISN</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($listGuru as $guru)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $guru->name }}</td>
                                        {{-- <td>{{ $guru->mata_pelajaran }}</td> --}}
                                        <td class="actions">
                                            <a class="action-link" href="{{ route('data.absen.guru.show', ['save_uuid_from_event' => $guru->uuid]) }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/eye.svg') }}" alt="img">
                                            </a>

                                            @if($dataUserAuth[1] == 'super_admin')
                                            <button class="action-button" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $guru->uuid }}">
                                                <img src="{{ asset('assets/dashboard/img/icons/delete.svg') }}" alt="img">
                                            </button>

                                            <div class="modal fade" id="modalDelete{{ $guru->uuid }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Apakah anda
                                                                yakin?</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            Data absen <span style="font-weight: bold">{{ $guru->nama_lengkap }}</span> akan terhapus!
                                                        </div>

                                                        <div class="modal-footer d-flex justify-content-end">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>

                                                            <form action="{{ route('data.absen.guru.delete') }}" method="POST" class="action-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="uuid" value="{{ $guru->uuid }}">
                                                                <button type="submit" class="btn btn-primary me-2">Submit</button>
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
